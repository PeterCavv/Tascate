<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class TascaAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $tasca = $request->route('tasca');
        if (auth()->user()->hasRole(Role::TASCA->value)){
            if ($tasca && $tasca->id !== auth()->user()->tasca->id) {
                return redirect()->route('tascas.index')->with('error', 'No tienes acceso a esta Tasca.');
            }
        }
        return $next($request);
    }
}
