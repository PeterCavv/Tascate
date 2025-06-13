<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class ManagerAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $manager = $request->route('manager');
        if (
            !(
                auth()->user()->hasRole(Role::EMPLOYEE->value) ||
                auth()->user()->hasRole(Role::ADMIN->value) ||
                auth()->user()->hasRole(Role::TASCA->value) ||
                auth()->user()->hasRole(Role::MANAGER->value)
            )
        ) {
            return redirect()->route('tascas.index')->with('error', 'Acceso denegado.');
        }

        if ($manager){
            if (auth()->user()->hasRole(Role::TASCA->value)) {
                if ($manager->tasca_id !== auth()->user()->tasca->id) {
                    return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este manager.');
                }
            }
            if (auth()->user()->hasRole(Role::MANAGER->value)) {
                if ($manager->tasca_id !== auth()->user()->manager->tasca_id) {
                    return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este manager.');
                }
            }
            if (auth()->user()->hasRole(Role::EMPLOYEE->value)) {
                if ($manager->tasca_id !== auth()->user()->employee->tasca_id) {
                    return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este manager.');
                }
            }
        }
        return $next($request);
    }
}
