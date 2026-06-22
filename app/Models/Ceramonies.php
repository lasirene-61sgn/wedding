<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ceramonies extends Model
{
    use SoftDeletes;
    protected $table = 'ceramonies';

    protected $fillable = [
        'host_id',
        'category_id',
        'venue_id',
        'ceramony_name',
        'ceramony_date',
        'ceramony_time',
        'ceramony_image',
        'selected_background_id',
        'is_main',
        'text_color',
        'details_color',
        'text_positions',
        'custom_canvas_texts',
    ];

    protected $casts = [
        'text_positions' => 'array',
        'custom_canvas_texts' => 'array',
    ];

    public function background()
    {
        return $this->belongsTo(CeramonyBackground::class, 'selected_background_id');
    }
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
