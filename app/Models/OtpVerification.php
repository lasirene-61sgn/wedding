<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';
    protected $fillable = [
        'identifier', 'otp', 'token', 'expires_at', 'verified_at', 'is_active'
    ];
}
