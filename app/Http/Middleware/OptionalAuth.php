<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OptionalAuth
{
    public function handle($request, Closure $next)
    {
        // Attempt to authenticate the user using Sanctum
        Auth::shouldUse('sanctum');
        return $next($request);
    }
}
