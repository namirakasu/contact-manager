<?php

namespace App\Http\Controllers;

use App\Mail\SimpleNotificationMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{      //show the contacts index page
    public function index()
    {      //get the contacts for the user
        $contacts = Contact::where('user_id', Auth::id())->latest()->get();
        return view('contacts.index', compact('contacts'));
    }

    public function create()      //show the contacts create page
    {
        return view('contacts.create');
    }

    public function store(Request $request)      //store the contact
    {
        $data = $request->validate([      //validate the data
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'contact' => 'required'
        ]);

        $data['user_id'] = Auth::id();      //set the user id
        $contact = Contact::create($data);

        // notify user (confirmation)
        Mail::to(Auth::user()->email)->send(new SimpleNotificationMail("Contact Added Successfully: {$contact->name}"));

        // notify admin
        if (env('ADMIN_MAIL')) {
            Mail::to(env('ADMIN_MAIL'))->send(new SimpleNotificationMail("New contact added by ".Auth::user()->name.": {$contact->name} ({$contact->email})"));
        }
        //return the view with the success message
        return redirect()->route('contacts.index')->with('success', 'Contact Added Successfully.');
    }
}

