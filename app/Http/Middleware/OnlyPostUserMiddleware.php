<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyPostUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->id === $request->route('post')->user_id) {
            return $next($request);
        }else{
            return redirect()->route('tascas.index')->with('error', 'Solo puedes actuar sobre tus posts');
        }
    }
}
