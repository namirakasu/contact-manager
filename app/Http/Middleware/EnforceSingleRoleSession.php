<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as SessionFacade;
use Illuminate\Support\Str;

class EnforceSingleRoleSession
{      //handle the request
    public function handle(Request $request, Closure $next)
    {
        $path = ltrim($request->path(), '/');      //get the path
        $isAdminArea = Str::startsWith($path, 'admin');

        // prefer cookie-based role switch to force single-role per browser
        $roleCookie = (string) $request->cookie('cm_role', '');

        $isAdminAuthed = Auth::guard('admin')->check();      //check if the admin is authenticated
        $isUserAuthed  = Auth::guard('web')->check();      //check if the user is authenticated

        if ($isAdminArea && $roleCookie === 'user' && $isAdminAuthed) {
            Auth::guard('admin')->logout();      //logout the admin
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }

        if (!$isAdminArea && $roleCookie === 'admin' && $isUserAuthed) {
            Auth::guard('web')->logout();      //logout the user
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        return $next($request);
    }
}


