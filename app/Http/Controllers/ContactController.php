<?php

namespace App\Http\Controllers;

use App\Mail\SimpleNotificationMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())->latest()->get();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'contact' => 'required'
        ]);

        $data['user_id'] = Auth::id();
        $contact = Contact::create($data);

        // notify user (confirmation)
        Mail::to(Auth::user()->email)->send(new SimpleNotificationMail("Contact Added Successfully: {$contact->name}"));

        // notify admin
        if (env('ADMIN_MAIL')) {
            Mail::to(env('ADMIN_MAIL'))->send(new SimpleNotificationMail("New contact added by ".Auth::user()->name.": {$contact->name} ({$contact->email})"));
        }

        return redirect()->route('contacts.index')->with('success', 'Contact Added Successfully.');
    }
}

