<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'host_id', 'videos'
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }
}
