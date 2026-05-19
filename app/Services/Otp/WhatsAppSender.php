<?php

namespace App\Services\Otp;

use App\Contracts\OtpSenderInterface;
use Override;

class WhatsAppSender implements OtpSenderInterface
{
    
    public function send(string $identifier, string $otp): bool
    {
        return true;
    }
}