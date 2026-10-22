<?php

namespace App\Http\Middleware;

use Closure;

class StudentMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'student') {
            return $next($request);
        }
        abort(403, 'Unauthorized access');
    }
}