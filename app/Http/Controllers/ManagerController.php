<?php

namespace App\Http\Controllers;

use App\Http\Requests\Manager\UpdateManagerRequest;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Enums\Role;
use App\Models\Tasca;

class ManagerController extends Controller
{
    use AuthorizesRequests;


    public function index()
    {
        $this->authorize('viewAny', Manager::class);

        $authUser = auth()->user();

        if($authUser->isAdmin()) {
            $managers = Manager::allManagers()->get();
        }else{
            $tascaId = $authUser->tasca_id;
            $manager = Manager::tascaManagers($tascaId)->get();

            if($manager->isEmpty()) {
                return redirect()
                    ->route('managers.index')
                    ->with('info', 'No hay managers asignados a esta Tasca. Por favor, crea uno.');
            }

            return redirect()
                ->route('managers.show', $manager->first());
        }

        return Inertia::render('Managers/Managers', [
            'managers' => $managers,
        ]);
    }

    public function show(Manager $manager)
    {
        $this->authorize('view', $manager);

        $user = $manager->user;
        $user->is_manager = true;
        $user->is_employee = false;

        return Inertia::render('Managers/ManagerShow', [
            'manager' => $manager->load(['user', 'tasca']),
            'user' => $user,
        ]);
    }

    public function edit(Manager $manager)
    {
        $this->authorize('update', $manager);

        if (auth()->user()->isAdmin()) {
            $tascas = Tasca::all();
        }
        else {
            $tascas = Tasca::where('id', $manager->tasca_id)->get();
        }

        return Inertia::render('Managers/ManagerEdit', [
            'manager' => $manager->load('user'),
            'tascas' => $tascas,
        ]);
    }

    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $this->authorize('update', $manager);

        $validated = $request->validated();

        DB::transaction(function () use ($manager, $validated) {
            $manager->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $manager->update([
                'tasca_id' => $validated['tasca_id'],
            ]);
        });

        return redirect()
            ->route('managers.show', $manager)
            ->with('success', 'Manager actualizado exitosamente.');
    }

    public function destroy(Manager $manager)
    {
        $this->authorize('delete', $manager);

        DB::transaction(function () use ($manager) {
            // Primero actualizamos los empleados que tienen este manager
            $manager->employees()->update(['manager_id' => null]);

            $user = $manager->user;
            $manager->delete();

            // Comprobar si el usuario tiene más trabajos (como empleado o manager)
            $hasMoreJobs = Employee::where('user_id', $user->id)->exists() ||
                          Manager::where('user_id', $user->id)->exists();
            if (!$hasMoreJobs) {
                $user->delete();
            }
        });

        return redirect()
            ->route('managers.index')
            ->with('success', 'Manager eliminado exitosamente.');
    }

    public function demote(Manager $manager)
    {
        $this->authorize('demote', $manager);

        $employee = Employee::create([
            'user_id' => $manager->user_id,
            'tasca_id' => $manager->tasca_id,
        ]);

        $user = $manager->user;

        $user->removeRole(Role::MANAGER->value);
        $user->assignRole(Role::EMPLOYEE->value);

        $manager->delete();
            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Manager degradado a empleado exitosamente.');
    }
}
