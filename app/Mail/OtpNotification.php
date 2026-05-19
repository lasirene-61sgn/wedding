<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $otp)
    {
        
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'OTP Code',
        );
    }

    public function content() : Content
    {
        return new Content(
            view: 'emails.otp-notification',
        );
    }
}