<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as SessionFacade;
use Illuminate\Support\Str;

class EnforceSingleRoleSession
{
    public function handle(Request $request, Closure $next)
    {
        $path = ltrim($request->path(), '/');
        $isAdminArea = Str::startsWith($path, 'admin');

        // Prefer cookie-based role switch to force single-role per browser
        $roleCookie = (string) $request->cookie('cm_role', '');

        $isAdminAuthed = Auth::guard('admin')->check();
        $isUserAuthed  = Auth::guard('web')->check();

        if ($isAdminArea && $roleCookie === 'user' && $isAdminAuthed) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }

        if (!$isAdminArea && $roleCookie === 'admin' && $isUserAuthed) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        return $next($request);
    }
}


