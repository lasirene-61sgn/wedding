<?php

namespace App\Services\Otp;

use App\Contracts\OtpSenderInterface;
use InvalidArgumentException;

class OtpSenderFactory
{
    public function make(string $channel): OtpSenderInterface
    {
        return match($channel) {
            'email' => new EmailSender(),
            'whatsapp' => new WhatsAppSender(),
            default => throw new InvalidArgumentException("Channel [$channel] is not supported"),
        };
    }
}