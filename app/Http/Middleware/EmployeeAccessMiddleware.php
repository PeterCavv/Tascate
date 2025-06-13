<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class EmployeeAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (
            !(
                auth()->user()->hasRole(Role::ADMIN->value) ||
                auth()->user()->hasRole(Role::EMPLOYEE->value) ||
                auth()->user()->hasRole(Role::TASCA->value) ||
                (auth()->user()->hasRole(Role::MANAGER->value) && auth()->user()->manager->delete_permission === 1)
            )
        ) {
            return redirect()->route('tascas.index')->with('error', 'Acceso denegado.');
        }

        return $next($request);
    }
}
