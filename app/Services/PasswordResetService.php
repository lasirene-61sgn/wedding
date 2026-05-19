<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Services\Otp\OtpSenderFactory;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function __construct(protected OtpSenderFactory $otpFactory)
    {
        
    }

    public function sendotp(string $identifier, string $channel) : bool
    {
        $otp = (string) random_int(100000, 999999);
        $token = Str::random(60);

        OtpVerification::updateOrCreate(
            ['identifier' => $identifier],
            [
                'otp' => $otp, 
                'token' => $token, 
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null,
            ]
        );
        $sender = $this->otpFactory->make($channel);
        return $sender->send($identifier, $otp);
    }
}