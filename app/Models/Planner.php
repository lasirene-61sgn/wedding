<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Planner extends Authenticatable
{
    protected $table = 'planner';

     protected $fillable = [
        'name', 'email', 'mobile', 'password'
    ];

    protected $casts =[
        'password' => 'hashed'
    ];
}
