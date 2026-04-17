<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Venue extends Authenticatable
{
    protected $table = 'venue';

     protected $fillable = [
        'name', 'email', 'mobile', 'password'
    ];

    protected $casts =[
        'password' => 'hashed'
    ];
}
