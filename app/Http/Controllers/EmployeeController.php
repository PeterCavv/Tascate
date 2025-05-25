<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
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

        if($authUser->isAdmin()) {
            $employees = Employee::allEmployees()->get();
        }else{
            $tascaId = $authUser->tasca_id;
            $employees = Employee::tascaEmployees($tascaId)->get();
        }

        return Inertia::render('Employees/Employees', [
            'employees' => $employees,
            'authUserId' => $authUser->id,
        ]);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);

        if ($employee->user->avatar) {
            $employee->user->avatar = asset('storage/' . $employee->user->avatar);
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

        $authUser = auth()->user();

//        ARREGLAR ESTO

//        // Get tascas based on user role
//        $tascas = match(true) {
//            $authUser->isAdmin() => \App\Models\Tasca::all(),
//            $authUser->isManager() => \App\Models\Tasca::where('id', $authUser->manager->tasca_id)->get(),
//            $authUser->isTasca() => \App\Models\Tasca::where('id', $authUser->tasca->id)->get(),
//            default => collect([])
//        };
//
//        // Get managers based on user role
//        $managers = match(true) {
//            $authUser->isAdmin() => \App\Models\Manager::with('user')->get(),
//            $authUser->isManager() => \App\Models\Manager::where('id', $authUser->manager->id)->with('user')->get(),
//            $authUser->isTasca() => \App\Models\Manager::where('tasca_id', $authUser->tasca->id)->with('user')->get(),
//            default => collect([])
//        };

        return Inertia::render('Employees/EmployeeForm', [
//            'tascas' => $tascas,
//            'managers' => $managers,
            'auth' => [
                'user' => [
                    'id' => $authUser->id,
                    'name' => $authUser->name,
                    'email' => $authUser->email,
                    'role' => $authUser->role,
                    'tasca_id' => $authUser->employee?->tasca_id ?? $authUser->manager?->tasca_id ?? $authUser->tasca?->id,
                    'manager_id' => $authUser->employee?->manager_id ?? $authUser->manager?->id,
                ]
            ]
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        return DB::transaction(function () use ($request) {
            $password = Str::random(12);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => Role::EMPLOYEE
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'tasca_id' => $request->tasca_id,
                'manager_id' => $request->manager_id
            ]);

//    AÑADIR NOTIFICACION CON LA CONTRASEÑA GENERADA

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado creado exitosamente.');
        });
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

        return DB::transaction(function () use ($request, $employee) {
            $employee->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $employee->update([
                'manager_id' => $request->manager_id
            ]);

            return redirect()
                ->route('employees.show', $employee)
                ->with('success', 'Empleado actualizado exitosamente.');
        });
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

            $employee->user->delete();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Empleado eliminado exitosamente.');
    }

    public function promote(Request $request, Employee $employee)
    {
        $this->authorize('promote', $employee);

        return DB::transaction(function () use ($employee) {
            $manager = Manager::create([
                'user_id' => $employee->user_id,
                'tasca_id' => $employee->tasca_id
            ]);

            $employee->user->update(['role' => Role::MANAGER]);

            $employee->delete();

            return redirect()
                ->route('managers.show', $manager)
                ->with('success', 'Empleado promovido a manager exitosamente.');
        });
    }
}
