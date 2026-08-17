<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitations';

    protected $fillable = [
        'host_id',
        'venue_id',
        'invite',
        'bride_name',
        'bride_number',
        'bride_email',
        'bride_father_name',
        'bride_mother_name',
        'groom_name',
        'groom_number',
        'groom_email',
        'groom_father_name',
        'groom_mother_name',
        'wedding_date',
        'wedding_time',
        'wedding_location',
        'pincode',
        'area_name',
        'district',
        'state',
        'circle',
        'country',
        'wedding_image',
        'selected_background_id',
        'selected_html_template',
        'theme',
        'is_main',
        'text_color',
        'details_color',
        'text_positions',
        'custom_canvas_texts',
        'setup_role',
        'creator_relationship',
        'wedding_category_id',
        'custom_wedding_category',
        'is_engagement_completed',
        'is_date_finalized',
        'is_venue_finalized',
        'venue_name',
        'current_city',
        'wedding_city',
        'wedding_state',
        'bride_display_name',
        'groom_display_name'
    ];

    protected $casts = [
        'text_positions' => 'array',
        'custom_canvas_texts' => 'array',
    ];

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function venue()
    {
        return $this->belongsTo(VenueName::class, 'venue_id');
    }
}
