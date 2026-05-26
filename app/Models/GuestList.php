<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestList extends Model
{
    use SoftDeletes;
    protected $table = 'guest_lists';

    protected $fillable = [
        'host_id', 'ceramony_id', 'guest_name',  'guest_number', 'guest_email', 'relation','gender',
        'alternate_number', 'whatsapp_number', 'age', 'complex', 'floor', 'door_no', 'street_name' ,'pincode',
        'area_name', 'district', 'state', 'circle',  'country', 'location_map', 'invitation_sent', 'sent_at',
        'send_via', 'assigned_ceremonies', 'status', 'category_id', 'rsvp_status'
    ];

    public function host(){
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function ceramony(){
        return $this->belongsTo(Ceramonies::class, 'ceramony_id');
    }

    public function category(){
        return $this->belongsTo(GuestCategory::class, 'category_id');
    }

    public function familyMembers(){
        return $this->hasMany(GuestFamilyMember::class, 'guest_list_id');
    }
}
