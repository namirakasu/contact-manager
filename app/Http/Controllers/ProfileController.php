<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{      //show the profile edit page
    public function edit()
    {      //get the user
        $user = Auth::user(); //retrievess the currently authenticated user
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)      //update the profile
    {
        /** @var User $user */      //get the user
        $user = Auth::user();

        $data = $request->validate([      //validate the data
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'gender'   => 'nullable|string',
            'dob'      => 'nullable|date|before_or_equal:today',
            'address'  => 'nullable|string',
            'contact'  => 'nullable|string',
            'profile_pic' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_pic')) {      //store the profile picture
            $path = $request->file('profile_pic')->store('profiles', 'public');
            $data['profile_pic'] = $path;
        }

        // update the profile

        $user->update($data);

        // go back to previous page 
        return redirect()->back()->with('success', 'Profile Updated Successfully.');
    }
}
