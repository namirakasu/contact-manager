<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'gender'   => 'nullable|string',
            'dob'      => 'nullable|date|before_or_equal:today',
            'address'  => 'nullable|string',
            'contact'  => 'nullable|string',
            'profile_pic' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profiles', 'public');
            $data['profile_pic'] = $path;
        }

        // Password updates removed from profile edit per requirements

        $user->update($data);

        // After update, go back to previous page (e.g., dashboard), then user can navigate to welcome
        return redirect()->back()->with('success', 'Profile Updated Successfully.');
    }
}
