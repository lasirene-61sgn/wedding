<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCategory extends Model
{
    protected $table = 'guest_categories';

    protected $fillable = [
        'host_id', 'category_name', 'ceremony_ids'
    ];

    protected $casts = [
        'ceremony_ids' => 'array',
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }
}
