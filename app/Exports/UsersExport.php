<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
	public function collection()
	{
		return User::all()->map(function ($user) {
			return [
				'ID' => $user->id,
				'Name' => $user->name,
				'Email' => $user->email,
				'Gender' => $user->gender ?? '',
				'Date of Birth' => $user->dob ?? '',
				'Address' => $user->address ?? '',
				'Contact' => $user->contact !== null ? '="' . $user->contact . '"' : '',
				'Created At' => $user->created_at ? '="' . $user->created_at->format('Y-m-d H:i:s') . '"' : '',
			];
		});
	}

	public function headings(): array
	{
		return ['ID', 'Name', 'Email', 'Gender', 'Date of Birth', 'Address', 'Contact', 'Created At'];
	}
}
