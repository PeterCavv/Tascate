<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\UpdateManagerRequest;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Enums\Role;

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

            return redirect()
                ->route('manager.show', $manager);
        }

        return Inertia::render('Managers/Managers', [
            'managers' => $managers,
        ]);
    }

    public function show(Manager $manager)
    {
        $this->authorize('view', $manager);

        if ($manager->avatar) {
            $manager->avatar = asset('storage/' . $manager->avatar);
        }

        return Inertia::render('Managers/ManagerShow', [
            'manager' => $manager,
        ]);
    }

    public function edit(Manager $manager)
    {
        $this->authorize('update', $manager);

        return Inertia::render('Managers/ManagerEdit', [
            'user' => $manager,
        ]);
    }

    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $this->authorize('update', $manager);

        $validated = $request->validated();

        $manager->update($validated);

        return redirect()
            ->route('managers.show', $manager)
            ->with('success', 'Manager actualizado exitosamente.');
    }

    public function destroy(Manager $manager)
    {
        $this->authorize('delete', $manager);

        $manager->delete();

        return redirect()
            ->route('managers.index')
            ->with('success', 'Manager eliminado exitosamente.');
    }

    public function demote(Manager $manager)
    {
        $this->authorize('demote', $manager);

        $employee = $manager;
        $employee->demote($manager);

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Manager degradado a empleado exitosamente.');
    }
}
