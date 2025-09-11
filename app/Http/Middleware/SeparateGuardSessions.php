<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeparateGuardSessions
{      //handle the request
    public function handle(Request $request, Closure $next)
    {
        $base = Str::slug(env('APP_NAME', 'laravel'));      //get the base name
        $defaultCookie = $base.'-session'; //user cookie name
        $adminCookie = $base.'-admin-session';  //session cookie name for admin

        $path = ltrim($request->path(), '/');      //get the path
        $isAdmin = Str::startsWith($path, 'admin');      //check if the path is admin

        // set session cookie name 
        config(['session.cookie' => $isAdmin ? $adminCookie : $defaultCookie]);
       //proceeding to the next request
        return $next($request);
    }
}



