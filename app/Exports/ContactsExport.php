<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Http\Response;

class ContactsExport
{
    public function download()
    {
        $contacts = Contact::with('user')->get();
        
        $filename = 'contacts_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Contact', 'User', 'Created At']);
            
            // Add data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    '="' . $contact->contact . '"',
                    $contact->user ? $contact->user->name : 'N/A',
                    '="' . $contact->created_at->format('Y-m-d H:i:s') . '"'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
