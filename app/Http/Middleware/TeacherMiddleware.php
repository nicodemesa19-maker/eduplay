<?php

namespace App\Http\Middleware;

use Closure;

class TeacherMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'teacher' && auth()->user()->is_approved) {
            return $next($request);
        }
        abort(403, 'Unauthorized access');
    }
}