<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestFamilyMember extends Model
{
    protected $fillable = [
        'guest_list_id', 'name', 'mobile', 'whatsapp_number', 'email', 'relation', 'gender', 'age'
    ];

    protected $guarded = [];
}
