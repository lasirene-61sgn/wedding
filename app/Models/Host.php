<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Host extends Authenticatable
{
    use Notifiable;
    protected $table = 'host';

     protected $fillable = [
        'name', 'email', 'mobile', 'password', 'status', 'created_by', 'package_id',
        'alternate_number', 'whatsapp_number', 'complex_name', 'floor', 
    'door_no', 'street_name', 'area', 'district', 'pincode', 
    'city', 'state', 'country', 'location_map', 'permissions'
    ];

    protected $casts =[
        'password' => 'hashed',
        'permissions' => 'array'
    ];

    public function package(){
        return $this->belongsTo(Package::class);
    }
    public function creator(){
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
