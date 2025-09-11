<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 

class AdminAuthController extends Controller
{      //show admin login page
    public function showLogin()
    {
        return view('admin.login');
    }
       //Process the login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);
          //Attempt to log in the admin
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Mark browser role as admin
            cookie()->queue(cookie('cm_role', 'admin', 60 * 24 * 7));
            return redirect()->route('admin.dashboard');
        }
            //If login fails, return with error message
        return back()->withErrors(['email' => 'Invalid admin credentials'])->onlyInput('email');
    }
        //Process the logout request
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}


