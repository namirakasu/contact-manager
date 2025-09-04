<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Response;

class UsersExport
{
    public function download()
    {
        $users = User::all();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Gender', 'Date of Birth', 'Address', 'Contact', 'Created At']);
            
            // Add data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->gender ?? '',
                    $user->dob ?? '',
                    $user->address ?? '',
                    '="' . ($user->contact ?? '') . '"',
                    '="' . $user->created_at->format('Y-m-d H:i:s') . '"'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
