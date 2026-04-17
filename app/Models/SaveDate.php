<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveDate extends Model
{
    protected $table = 'save_dates';

    protected $fillable = [
        'host_id', 'invitation_id', 'image', 'message'
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function invitation(){
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }
}
