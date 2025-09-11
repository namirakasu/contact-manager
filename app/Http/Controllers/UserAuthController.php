<?php

namespace App\Http\Controllers;

use App\Mail\SimpleNotificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
 

class UserAuthController extends Controller
{       //show the register page
    public function showRegister()
    {      //return the view with the register page
        return view('user.register');
    }
    //process the register request
    public function register(Request $request)
    {
        $data = $request->validate([      //validate the data
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'gender'   => 'nullable|string',
            'dob'      => 'nullable|date|before_or_equal:today',
            'address'  => 'nullable|string',
            'contact'  => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        // welcome email to user
        Mail::to($user->email)->send(new SimpleNotificationMail("Welcome {$user->name}! Your account has been created."));

        // notify admin
        if (env('ADMIN_MAIL')) {
            Mail::to(env('ADMIN_MAIL'))->send(new SimpleNotificationMail("New user registered: {$user->name} ({$user->email})"));
        }
        // optional copy of registration to fixed mailbox
        if (env('REGISTER_NOTIFY_MAIL')) {
            Mail::to(env('REGISTER_NOTIFY_MAIL'))->send(new SimpleNotificationMail("New user registered: {$user->name} ({$user->email})"));
        }

        return redirect()->route('login')->with('success', 'Registered successfully! Please login.');
    }

    public function showLogin()
    {      //return the view with the login page
        return view('user.login');
    }
   
    public function login(Request $request)
    {      //validate the data
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        //attempt to log in the user
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // mark browser role as user
            cookie()->queue(cookie('cm_role', 'user', 60 * 24 * 7));
            // send login success email
            try {
                $user = Auth::guard('web')->user(); //get the user
                if ($user && $user->email) {
                    $message = "Login successful: {$user->name}";
                    Mail::to($user->email)->send(new SimpleNotificationMail($message));
                    // optional copy to a fixed mailbox for monitoring
                    $notify = env('LOGIN_NOTIFY_MAIL', env('ADMIN_MAIL'));
                    if (!empty($notify)) {
                        Mail::to($notify)->send(new SimpleNotificationMail($message.' (copy)'));
                    }
                }
            } catch (\Throwable $e) {

            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {      //logout the user
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
