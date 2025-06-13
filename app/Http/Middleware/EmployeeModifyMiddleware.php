<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class EmployeeModifyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $employee = $request->route('employee');
        if (
            !(
                auth()->user()->hasRole(Role::ADMIN->value) ||
                auth()->user()->hasRole(Role::TASCA->value) ||
                (auth()->user()->hasRole(Role::MANAGER->value) && auth()->user()->manager->delete_permission === 1)
            )
        ) {
            return redirect()->route('tascas.index')->with('error', 'Acceso denegado.');
        }

        if (auth()->user()->hasRole(Role::MANAGER->value)) {
            if ($employee->tasca_id !== auth()->user()->manager->tasca_id) {
                return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este empleado.');
            }
        }
        if (auth()->user()->hasRole(Role::TASCA->value)) {
            if ($employee->tasca_id !== auth()->user()->tasca->id) {
                return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este empleado.');
            }
        }

        return $next($request);
    }
}
