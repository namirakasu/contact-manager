<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeparateGuardSessions
{
    public function handle(Request $request, Closure $next)
    {
        $base = Str::slug(env('APP_NAME', 'laravel'));
        $defaultCookie = $base.'-session';
        $adminCookie = $base.'-admin-session';

        $path = ltrim($request->path(), '/');
        $isAdmin = Str::startsWith($path, 'admin');

        // Set session cookie name per area before StartSession runs
        config(['session.cookie' => $isAdmin ? $adminCookie : $defaultCookie]);

        return $next($request);
    }
}



