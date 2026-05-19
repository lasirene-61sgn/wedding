<?php

namespace App\Contracts;

interface OtpSenderInterface
{
    public function send(string $identifier, string $otp) : bool ;
}