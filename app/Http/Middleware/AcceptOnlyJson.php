<?php

namespace App\Http\Middleware;

use Closure;

class AcceptOnlyJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->expectsJson()) {
            abort(405, 'Backend accept only json communication.');
        }

        return $next($request);
    }
}
