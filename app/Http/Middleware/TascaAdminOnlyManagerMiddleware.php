<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class TascaAdminOnlyManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (!auth()->user()->hasRole(Role::TASCA->value) && !auth()->user()->hasRole(Role::ADMIN->value)) {
            return redirect()->route('tascas.index')->with('error', 'Acceso denegado.');
        }

        $manager = $request->route('manager');
        if ($manager) {
            if (auth()->user()->hasRole(Role::TASCA->value)) {
                if ($manager->tasca_id !== auth()->user()->tasca->id) {
                    return redirect()->route('tascas.index')->with('error', 'No tienes acceso a este manager.');
                }
            }
        }
        return $next($request);
    }
}
