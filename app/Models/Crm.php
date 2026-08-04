<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crm extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'attempts_count',
    ];
}
