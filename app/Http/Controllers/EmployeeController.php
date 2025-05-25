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

class EmployeeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', Employee::class);

        $employees = User::where('role', Role::EMPLOYEE->value)->get();

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }

        return Inertia::render('Employees/Employees', [
            'employees' => $employees,
            'authUserId' => $authUserId,
        ]);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        if ($employee->avatar) {
            $employee->avatar = asset('storage/' . $employee->avatar);
        }

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
        } else {
            $authUserId = null;
        }

        return Inertia::render('Employees/EmployeeShow', [
            'employee' => $employee,
            'authUserId' => $authUserId,
            'csrfToken' => csrf_token(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Employee::class);
        return Inertia::render('Employee/EmployeeForm');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Employee::class);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('employees', 'user_id')
            ],
            'tasca_id' => [
                'required',
                'exists:tascas,id'
            ],
            'manager_id' => [
                'required',
                'exists:managers,id'
            ]
        ]);

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

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $validated = $request->validate([
            'manager_id' => [
                'required',
                'exists:managers,id'
            ]
        ]);

        $employee->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }

    public function promote(Request $request, Employee $employee)
    {
        $this->authorize('promote', $employee);

        return DB::transaction(function () use ($employee) {
            // Create manager record
            $manager = Manager::create([
                'user_id' => $employee->user_id,
                'tasca_id' => $employee->tasca_id
            ]);

            // Delete employee record
            $employee->delete();

            return redirect()
                ->route('managers.show', $manager)
                ->with('success', 'Empleado promovido a manager exitosamente.');
        });
    }
}
