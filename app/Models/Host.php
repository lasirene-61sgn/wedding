<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Host extends Authenticatable
{
    use Notifiable, SoftDeletes;
    protected $table = 'host';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'status',
        'created_by',
        'package_id',
        'package_status',
        'alternate_number',
        'whatsapp_number',
        'is_password_set',
        'complex_name',
        'floor',
        'door_no',
        'street_name',
        'area',
        'district',
        'pincode',
        'city',
        'state',
        'country',
        'location_map',
        'permissions'
    ];

    protected $casts = [
        'password' => 'hashed',
        'permissions' => 'array'
    ];

    public static function getDefaultPermissions(){
        return ['ceremonies', 'gallery', 'invitation', 'save-the-date', 'guest-list', 'reports', 'categories'];
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}