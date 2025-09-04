<?php

namespace App\Http\Controllers;

use App\Exports\ContactsExport;
use App\Exports\UsersExport;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function dashboard()
    {
        $counts = [
            'users'    => User::count(),
            'contacts' => Contact::count(),
        ];

        return view('admin.dashboard', [
            'counts' => $counts,
        ]);
    }

    public function users(Request $request)
    {
        $searchQuery = trim((string) $request->query('q'));
        $gender = trim((string) $request->query('gender'));

        $users = User::query()
            ->when($searchQuery !== '', function ($query) use ($searchQuery) {
                $query->where(function ($inner) use ($searchQuery) {
                    $inner->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('email', 'like', "%{$searchQuery}%")
                        ->orWhere('address', 'like', "%{$searchQuery}%")
                        ->orWhere('contact', 'like', "%{$searchQuery}%");
                });
            })
            ->when($gender !== '', function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->latest()
            ->get();

        return view('admin.users', [
            'users' => $users,
            'q' => $searchQuery,
            'gender' => $gender,
        ]);
    }

    public function contacts(Request $request)
    {
        $searchQuery = trim((string) $request->query('q'));

        $contacts = Contact::with('user')
            ->when($searchQuery !== '', function ($query) use ($searchQuery) {
                $query->where(function ($inner) use ($searchQuery) {
                    $inner->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('email', 'like', "%{$searchQuery}%")
                        ->orWhere('contact', 'like', "%{$searchQuery}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.contacts', [
            'contacts' => $contacts,
            'q' => $searchQuery,
        ]);
    }

    public function exportUsers()
    {
        $export = new UsersExport();
        return $export->download();
    }

    public function exportContacts()
    {
        $export = new ContactsExport();
        return $export->download();
    }
}


