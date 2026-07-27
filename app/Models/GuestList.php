<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestList extends Model
{
    use SoftDeletes;
    protected $table = 'guest_lists';

    protected $fillable = [
        'host_id', 'ceramony_id', 'uuid', 'guest_name',  'guest_number', 'guest_email', 'relation','gender',
        'alternate_number', 'whatsapp_number', 'age', 'complex', 'floor', 'door_no', 'street_name' ,'pincode',
        'area_name', 'district', 'state', 'circle',  'country', 'location_map', 'invitation_sent', 'sent_at',
        'send_via', 'assigned_ceremonies', 'status', 'ceremony_status', 'category_id', 'rsvp_status', 'save_date_sent'
    ];

    protected $casts = [
        'ceremony_status' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

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

    public function getDetailedAssignedCeremoniesAttribute()
    {
        if (!$this->category || empty($this->category->ceremony_ids)) {
            return $this->assigned_ceremonies;
        }

        $details = [];
        $ceremonyIds = collect($this->category->ceremony_ids)->pluck('id')->filter()->toArray();
        $ceremonies = \App\Models\Ceramonies::whereIn('id', $ceremonyIds)->get()->keyBy('id');

        foreach ($this->category->ceremony_ids as $c) {
            $id = $c['id'] ?? null;
            $type = $c['group_type'] ?? null;
            
            if ($id && isset($ceremonies[$id])) {
                $name = $ceremonies[$id]->ceramony_name;
                $count = 1;
                if ($type === 'family') $count = 4;
                elseif ($type === 'couple') $count = 2;
                
                $typeLabel = ucfirst($type ?? 'Single');
                $details[] = "<div class='mb-1'><strong>{$name}</strong> <span class='badge bg-light text-dark border'>{$typeLabel} - {$count} Guests</span></div>";
            }
        }
        
        if (empty($details)) {
            return $this->assigned_ceremonies;
        }
        
        return implode('', $details);
    }
}
