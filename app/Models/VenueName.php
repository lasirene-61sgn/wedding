<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueName extends Model
{
    protected $table = 'venue_names';

    protected $fillable = [
        'host_id', 'venue_name', 'pincode', 'area_name', 'district', 'state', 'circle', 'country', 'venue_address', 'wedding_location', 'location_map'
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }
}
