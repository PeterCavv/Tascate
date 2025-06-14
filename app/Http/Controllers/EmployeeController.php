<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Employee::class);


        $authUser = auth()->user();

        if(!$authUser->isAdmin()) {
            $tascaId = $authUser->tasca_id;
            $manager = Manager::where('tasca_id', $tascaId)
                ->first();
            if ($manager) {
                $manager->load('user:id,name,email');
            }
            $employees = Employee::tascaEmployees($tascaId)->get();
        }else{
            $employees = Employee::allEmployees()->get();
        }

        if ($authUser->isManager()){
            $manager = $authUser->manager;
            if ($manager) {
                $manager->load('user:id,name,email');
            }
            $employees = Employee::where('manager_id', $authUser->manager->id)
                ->with(['user:id,name,email'])
                ->get();
        }

        if ($authUser->isTasca()) {
            $manager = $authUser->tasca->manager;
             if ($manager) {
                 $manager->load('user:id,name,email');
             }
            $employees = Employee::where('tasca_id', $authUser->tasca->id)
                ->with(['user:id,name,email'])
                ->get();
        }

        return Inertia::render('Employees/Employees', [
            'manager' => $manager ?? null,
            'employees' => $employees->load('tasca', 'user', 'manager'),
        ]);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        $employee_id = $employee->id;
        $employee = Employee::oneEmployee($employee_id)->first();

        $user = $employee->user;
        $user->is_manager = false;
        $user->is_employee = true;

        return Inertia::render('Employees/EmployeeShow', [
            'employee' => $employee,
            'user' => $user,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Employee::class);

        $authUser = auth()->user();

        if($authUser->isAdmin()) {
            $tascas = Tasca::with(['manager.user:id,name,email'])->get();
            return Inertia::render('Employees/EmployeeForm', [
                'tascas' => $tascas,
            ]);
        }
        if ($authUser->isTasca()) {
            $tascas = Tasca::where('id', $authUser->tasca->id)
                ->with(['manager.user:id,name,email'])
                ->get();
            return Inertia::render('Employees/EmployeeForm', [
                'tascas' => $tascas,
            ]);
        }
        if ($authUser->isManager()) {
            $tascas = Tasca::where('id', $authUser->manager->tasca_id)
                ->with(['manager.user:id,name,email'])
                ->get();
            return Inertia::render('Employees/EmployeeForm', [
                'tascas' => $tascas,
            ]);
        }
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole(Role::EMPLOYEE->value);

        $employee = Employee::create([
            'user_id' => $user->id,
            'tasca_id' => $validated['tasca_id'],
            'manager_id' => $validated['manager_id'],
        ]);

        return redirect()
            ->route('employees.show', $employee)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.created'),
                'detail' => __('messages.toast.employee_created'),
            ]);;
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update', $employee);

        $managers = $employee->tasca->manager()
            ->with('user:id,name,email')
            ->get();

        foreach ($managers as $manager) {
            $manager->load('user:id,name,email');
        }
        return Inertia::render('Employees/EditEmployee', [
            'employee' => $employee->load('tasca', 'user'),
            'managers' => $managers,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $validated = $request->validated();

        $employee->user->update($validated);
        $employee->update([
            'manager_id' => $validated['manager_id'],
        ]);

        return redirect()
            ->route('employees.show', $employee)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.updated'),
                'detail' => __('messages.toast.employee_updated'),
            ]);
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $employee->delete();

            // Comprobar si el usuario tiene más empleos
            $hasMoreJobs = Employee::where('user_id', $user->id)->exists();
            if (!$hasMoreJobs) {
                $user->delete();
            }
        });

        return redirect()
            ->route('employees.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.deleted'),
                'detail' => __('messages.toast.employee_deleted'),
            ]);
    }

    public function promote(Employee $employee)
    {
        $manager = Manager::where('tasca_id', $employee->tasca_id)
            ->with('user:id,name,email')
            ->get();
        if ($manager->count() > 0) {
            return redirect()
                ->route('employees.index')
                ->with('toast', [
                    'severity' => 'warn',
                    'summary' => __('messages.toast.error'),
                    'detail' => __('messages.toast.error_promoting'),
                ]);
        }

        $this->authorize('promote', $employee);
        $manager = Manager::create([
            'user_id' => $employee->user_id,
            'tasca_id' => $employee->tasca_id,
        ]);

        $user = $employee->user;

        $user->removeRole(Role::EMPLOYEE->value);
        $user->assignRole(Role::MANAGER->value);

        $employee->delete();
            return redirect()
                ->route('managers.show', $manager)
                ->with('toast', [
                    'severity' => 'success',
                    'summary' => __('messages.toast.promoted'),
                    'detail' => __('messages.toast.employee_promoted', ['name' => $employee->user->name]),
                ]);
    }
    public function demote(Manager $manager)
    {
        $this->authorize('demote', $manager);

        $user = $manager->user;

        if ($user->hasRole(Role::MANAGER->value)) {
            $user->removeRole(Role::MANAGER->value);
        }

        $user->assignRole(Role::EMPLOYEE->value);

        $employee = Employee::create([
            'user_id' => $user->id,
            'tasca_id' => $manager->tasca_id,
            'manager_id' => null, // El empleado no tiene manager
        ]);

        $manager->delete();


        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado degradado a empleado exitosamente.');
    }
}
