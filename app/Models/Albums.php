<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Albums extends Model
{
    protected $table = 'albums';

    protected $fillable = [
        'host_id', 'album_name', 'album_images'
    ];

    protected $casts = [
        'album_images' => 'array',
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }
}
