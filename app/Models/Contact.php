<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{    //fillable fields
    protected $fillable = ['user_id','name','email','contact'];
    // contact belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
