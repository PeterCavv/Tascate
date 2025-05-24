<?php

namespace App\Http\Controllers;

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
        $this->authorize('view', Manager::class);

        $managers = User::where('role', Role::MANAGER->value)->get();

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }

        return Inertia::render('Managers/Index', [
            'managers' => $managers,
            'authUserId' => $authUserId,
        ]);
    }

    public function show(Manager $manager)
    {
        $this->authorize('view', $manager);

        if ($manager->avatar) {
            $manager->avatar = asset('storage/' . $manager->avatar);
        }

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }

        return Inertia::render('Managers/ManagersShow', [
            'manager' => $manager,
            'authUserId' => $authUserId,
            'csrfToken' => csrf_token(),
        ]);
    }

    public function edit(Manager $manager)
    {
        $this->authorize('update', $manager);

        return Inertia::render('Managers/ManagersEdit', [
            'user' => $manager,
        ]);
    }

    public function update(Request $request, Manager $manager)
    {
        $this->authorize('update', $manager);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $manager->avatar = $path;
        }
        $validated = $request->validated();

        $manager->name = $validated['name'];
        $manager->email = $validated['email'];
        $manager->save();

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

        return DB::transaction(function () use ($manager) {
            // Create employee record
            $employee = Employee::create([
                'user_id' => $manager->user_id,
                'tasca_id' => $manager->tasca_id,
            ]);

            // Delete manager record
            $manager->delete();

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Manager degradado a empleado exitosamente.');
        });
    }
}
