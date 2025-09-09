<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
	public function collection()
	{
		return Contact::with('user')->get()->map(function ($contact) {
			return [
				'ID' => $contact->id,
				'Name' => $contact->name,
				'Email' => $contact->email,
				'Contact' => $contact->contact !== null ? '="' . $contact->contact . '"' : '',
				'User' => $contact->user ? $contact->user->name : 'N/A',
				'Created At' => $contact->created_at ? '="' . $contact->created_at->format('Y-m-d H:i:s') . '"' : '',
			];
		});
	}

	public function headings(): array
	{
		return ['ID', 'Name', 'Email', 'Contact', 'User', 'Created At'];
	}
}
