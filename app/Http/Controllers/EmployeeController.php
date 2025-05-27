<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Employee\StoreManagerRequest;
use App\Http\Requests\Employee\UpdateManagerRequest;
use App\Models\Employee;
use App\Models\Manager;
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

        if ($employee->user->avatar) {
            $employee->user->avatar = asset('storage/' . $employee->user->avatar);
        }

        return Inertia::render('Employees/EmployeeShow', [
            'employee' => $employee,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Employee::class);

        return Inertia::render('Employees/EmployeeForm');
    }

    public function store(StoreManagerRequest $request)
    {
        $this->authorize('create', Employee::class);

        $validated = $request->validated();

        $employee = Employee::create($validated);

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

    public function update(UpdateManagerRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $validated = $request->validated();

        $employee->update($validated);

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

            $employee->user->delete();

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
