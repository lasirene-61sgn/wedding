<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitations';

    protected $fillable = [
        'host_id',
        'venue_id',
        'invite',
        'bride_name',
        'bride_number',
        'bride_email',
        'bride_father_name',
        'bride_mother_name',
        'groom_name',
        'groom_number',
        'groom_email',
        'groom_father_name',
        'groom_mother_name',
        'wedding_date',
        'wedding_time',
        'wedding_location',
        'pincode',
        'area_name',
        'district',
        'state',
        'circle',
        'country',
        'wedding_image',
        'theme',
        'is_main'
    ];

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function venue()
    {
        return $this->belongsTo(VenueName::class, 'venue_id');
    }
}
