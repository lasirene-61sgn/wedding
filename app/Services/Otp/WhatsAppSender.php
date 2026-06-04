<?php

namespace App\Services\Otp;

use App\Contracts\OtpSenderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppSender implements OtpSenderInterface
{
    public function send(string $identifier, string $otp): bool
    {
        $authKey = config('services.msg91.authkey', env('MSG91_AUTH_KEY'));
        $integratedNumber = config('services.msg91.whatsapp_number', '919360777089');
        $url = 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/';

        $mobileNumber = preg_replace('/[^0-9]/', '', $identifier);
        if (strlen($mobileNumber) === 10) {
            $mobileNumber = '91' . $mobileNumber;
        }

        if (empty($authKey)) {
            Log::error("MSG91 Auth Key is missing in configuration.");
            return false;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey'      => $authKey
        ])->post($url, [
            "integrated_number" => $integratedNumber,
            "content_type"      => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type"              => "template",
                "template" => [
                    "name"      => "logintest",
                    "language"  => [
                        "code"   => "en",
                        "policy" => "deterministic"
                    ],
                    "namespace"         => "bc3735fb_a2e9_4e83_8b62_377bca25c09f",
                    "to_and_components" => [
                        [
                            "to" => [
                                $mobileNumber
                            ],
                            "components" => [
                                "body_1" => [
                                    "type"  => "text",
                                    "value" => $otp 
                                ],
                                "button_1" => [
                                    "subtype" => "url",
                                    "type"    => "text",
                                    "value"   => $otp 
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            Log::info("MSG91 WhatsApp OTP Response Body: " . $response->body());
            return true;
        }

        Log::error("MSG91 WhatsApp OTP API Error: " . $response->status() . " - " . $response->body());
        return false;
    }
}