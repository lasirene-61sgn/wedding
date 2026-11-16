<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveDate extends Model
{
    protected $table = 'save_dates';

    protected $fillable = [
        'host_id',
        'invitation_id',
        'category_id',
        'ceremony_id',
        'venue_id',
        'name_order',
        'use_first_name',
        'image',
        'message',
        'template',
    ];

    protected $casts = [
        'use_first_name' => 'boolean',
    ];

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryVenue::class, 'category_id');
    }

    public function ceremony()
    {
        return $this->belongsTo(Ceramonies::class, 'ceremony_id');
    }

    public function venue()
    {
        return $this->belongsTo(VenueName::class, 'venue_id');
    }
}
