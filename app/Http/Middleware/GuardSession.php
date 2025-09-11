<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardSession
{      //handle the request
    public function handle(Request $request, Closure $next, string $guard)
    {
        // ensure session is started for the specified guard
        
       
        Auth::shouldUse($guard);      
        return $next($request);
    }
}


