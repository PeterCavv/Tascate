<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\StoreManagerRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use App\Rules;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            $employees = Employee::tascaEmployees($tascaId)->get();
        }else{
            $employees = Employee::allEmployees()->get();
        }

        return Inertia::render('Employees/Employees', [
            'employees' => $employees,
        ]);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        $employee_id = $employee->id;

        $employee = Employee::oneEmployee($employee_id)->first();

        return Inertia::render('Employees/EmployeeShow', [
            'employee' => $employee,
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
                'auth' => auth()->user()->load('employee'),
            ]);
        }

        return Inertia::render('Employees/EmployeeForm');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
            'role' => Role::EMPLOYEE->value,
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'tasca_id' => $validated['tasca_id'],
            'manager_id' => $validated['manager_id'] ?? null,
        ]);

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado creado exitosamente.');
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update', $employee);

        return Inertia::render('Employees/EditEmployee', [
            'employee' => $employee,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $validated = $request->validated();

        $employee->user->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado actualizado exitosamente.');
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
            ->with('success', 'Empleado eliminado exitosamente.');
    }

    public function promote(Employee $employee)
    {
        $this->authorize('promote', $employee);

        $manager = $employee;
        $manager->promote($employee);

            return redirect()
                ->route('managers.show', $manager)
                ->with('success', 'Empleado promovido a manager exitosamente.');

    }
}
