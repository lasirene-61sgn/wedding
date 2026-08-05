<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelAddon extends Model
{
    protected $table = 'channel_addons';

    protected $fillable = [
        'name',
        'type',
        'count',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function purchases()
    {
        return $this->hasMany(HostAddonPurchase::class, 'addon_id');
    }

    /**
     * Scope to only active add-ons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
