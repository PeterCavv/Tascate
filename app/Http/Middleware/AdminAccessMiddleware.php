<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasRole(Role::ADMIN->value)) {
            return $next($request);
        }

        return redirect()->route('tascas.index')->with('error', 'Admins only.');
    }
}
