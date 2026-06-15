<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostFamilyDetails extends Model
{
    protected $table = 'host_family_details';

    protected $fillable = [
        'host_id',
        'selected_background_id',
        'textone',
        'texttwo',
        'textthree',
        'textfour',
        'textfive',
        'textsix',
        'textseven',
        'topic_title_one',
        'topic_title_two',
        'topic_title_three',
        'topic_title_four',
        'topic_title_five',
        'topic_title_six',
        'is_active'
    ];

    public function background()
    {
        // Maps the selected_background_id foreign key to your CeramonyBackground model
        return $this->belongsTo(CeramonyBackground::class, 'selected_background_id');
    }
}
