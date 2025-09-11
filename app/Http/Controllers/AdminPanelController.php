<?php

namespace App\Http\Controllers;
//import the necessary classes
use App\Exports\ContactsExport;
use App\Exports\UsersExport;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

class AdminPanelController extends Controller
{      //show admin dashboard
	public function dashboard()
	{     //to get total users and contacts
		$counts = [
			'users'    => User::count(),
			'contacts' => Contact::count(),
		];
         //return the view with the counts
		return view('admin.dashboard', [
			'counts' => $counts,
		]);
	}
         // Shows a filtered list of users with optional search and gender filte
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
			}) // Filters by gender if provided
			->when($gender !== '', function ($query) use ($gender) {
				$query->where('gender', $gender);
			})
			->latest() // Sorts by latest first
			->get(); //  Execute the query and get results

		return view('admin.users', [
			'users' => $users,
			'q' => $searchQuery,
			'gender' => $gender,
		]);
	}
      // Shows a filtered list of contacts (with optional search
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
			//return the view with the contacts
		return view('admin.contacts', [
			'contacts' => $contacts,
			'q' => $searchQuery,
		]);
	}
		// Export users to CSV or Excel
	public function exportUsers(Request $request)
	{
		$format = strtolower((string) $request->query('format', 'xlsx')); // default to xlsx to preserve styling and images
		$writerType = $format === 'xlsx' ? ExcelWriter::XLSX : ExcelWriter::CSV; //set the writer type
		$extension = $format === 'xlsx' ? 'xlsx' : 'csv'; //set the extension
		$filename = 'users_' . date('Y-m-d_H-i-s') . '.' . $extension; //set the filename
		return Excel::download(new UsersExport(), $filename, $writerType); //download the users export
	}

	public function exportContacts(Request $request)
	{
		$format = strtolower((string) $request->query('format', 'xlsx')); // default to xlsx
		$writerType = $format === 'xlsx' ? ExcelWriter::XLSX : ExcelWriter::CSV;
		$extension = $format === 'xlsx' ? 'xlsx' : 'csv';
		$filename = 'contacts_' . date('Y-m-d_H-i-s') . '.' . $extension;
		return Excel::download(new ContactsExport(), $filename, $writerType);
	}
}


