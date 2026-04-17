<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pictures extends Model
{
    protected $table = 'pictures';
    protected $fillable = [
    'host_id', 
    'picture'
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }
}
