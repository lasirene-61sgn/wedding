<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'package_name', 'price', 'guest_limit',  'validity', 'invitaion', 'rsvp', 'ceramonies', 'reports', 'gallery', 
        'package_description', 'wishboard', 'dcgqrcode', 'vaf' ,'invite_limit',
    ];

    public function hosts(){
        return $this->hasMany(Host::class);
    }

    public function customFeatures()
    {
        return $this->hasMany(PackageFeature::class, 'package_id');
    }
}
