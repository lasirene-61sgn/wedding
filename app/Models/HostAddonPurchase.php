<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostAddonPurchase extends Model
{
    protected $table = 'host_addon_purchases';

    protected $fillable = [
        'host_id',
        'addon_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'amount_paid',
        'status',
    ];

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function addon()
    {
        return $this->belongsTo(ChannelAddon::class, 'addon_id');
    }
}
