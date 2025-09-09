<?php

namespace App\Http\Controllers;

use App\Exports\ContactsExport;
use App\Exports\UsersExport;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

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

	public function exportUsers(Request $request)
	{
		$format = strtolower((string) $request->query('format', 'csv'));
		$writerType = $format === 'xlsx' ? ExcelWriter::XLSX : ExcelWriter::CSV;
		$extension = $format === 'xlsx' ? 'xlsx' : 'csv';
		$filename = 'users_' . date('Y-m-d_H-i-s') . '.' . $extension;
		return Excel::download(new UsersExport(), $filename, $writerType);
	}

	public function exportContacts(Request $request)
	{
		$format = strtolower((string) $request->query('format', 'csv'));
		$writerType = $format === 'xlsx' ? ExcelWriter::XLSX : ExcelWriter::CSV;
		$extension = $format === 'xlsx' ? 'xlsx' : 'csv';
		$filename = 'contacts_' . date('Y-m-d_H-i-s') . '.' . $extension;
		return Excel::download(new ContactsExport(), $filename, $writerType);
	}
}


