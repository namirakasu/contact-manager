<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// admin model
class Admin extends Authenticatable
{
    use Notifiable;
    // fillable fields
    protected $fillable = [
        'name', 'email', 'password'
    ];
    // hidden fields
    protected $hidden = [
        'password', 'remember_token',
    ];
}


