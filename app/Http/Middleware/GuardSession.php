<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardSession
{
    public function handle(Request $request, Closure $next, string $guard)
    {
        // Ensure session is started for the specified guard
        // This middleware is a placeholder to match prior usage
        // It allows grouping routes by intended guard context
        Auth::shouldUse($guard);
        return $next($request);
    }
}


