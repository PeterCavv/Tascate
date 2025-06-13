<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyCommentUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('comment')->user_id) {
            return $next($request);
        }

        return redirect()->route('tascas.index')->with('error', 'Solo puedes actuar sobre tus comentarios');
    }
}
