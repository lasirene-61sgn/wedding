<?php

namespace App\Services\Otp;

use App\Contracts\OtpSenderInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpNotification;

class EmailSender implements OtpSenderInterface
{
    public function send(string $identifier, string $otp): bool
    {
        Mail::to($identifier)->send(new OtpNotification($otp));
        return true;
    }
}