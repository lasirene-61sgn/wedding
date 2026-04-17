<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'package_name', 'package_description', 'price', 'guest_limit', 'invite_limit',
    ];

    public function hosts(){
        return $this->hasMany(Host::class);
    }
}
