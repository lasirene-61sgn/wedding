<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceramonies extends Model
{
    protected $table = 'ceramonies';

    protected $fillable = [
        'host_id', 'category_id', 'ceramony_name', 'ceramony_date', 'ceramony_time', 
        'venue_id', 'ceramony_image', 'is_main'
    ];
    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function category(){
        return $this->belongsTo(CategoryVenue::class, 'category_id');
    }

    public function venue(){
        return $this->belongsTo(VenueName::class, 'venue_id');
    }
}
