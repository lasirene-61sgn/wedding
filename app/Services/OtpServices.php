<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpServices
{
    /**
     * Generate, store, and send an OTP via the specified channel.
     * * @param mixed $user
     * @param string $channel ('email' or 'whatsapp')
     * @return string|false Returns token on success, false on failure.
     */
    public function generateAndSend($user, string $channel)
    {
        $otp = (string) rand(100000, 999999);
        $token = Str::random(60);
        $identifier = ($channel === 'email') ? $user->email : $user->mobile;

        // Clean up any existing OTPs for this identifier
        OtpVerification::where('identifier', $identifier)->delete();

        // Securely store the OTP in the database using Hashing
        OtpVerification::create([
            'identifier' => $identifier,
            'otp'        => Hash::make($otp), 
            'token'      => $token,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Dispatch via the chosen channel and verify success
        if ($channel === 'email') {
            $sent = $this->sendEmail($user, $otp);
        } else {
            $sent = $this->sendWhatsapp($user, $otp);
        }

        // Only return the token if the external API accepted the message
        return $sent ? $token : false;
    }

    /**
     * Dispatch OTP via MSG91 Email API.
     */
    protected function sendEmail($user, $otp)
    {
        // Implement your MSG91 Email logic here.
        // Ensure it returns true on success, false on failure.
        return true; 
    }

    /**
     * Dispatch OTP via MSG91 WhatsApp API.
     */
    protected function sendWhatsapp($user, $otp)
    {
        $authKey = config('services.msg91.authkey', env('MSG91_AUTH_KEY'));
        $integratedNumber = config('services.msg91.whatsapp_number', '919360777089');
        $url = 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/';

        // Sanitize the phone number: remove spaces, dashes, and leading '+' signs
        // MSG91 expects a clean country code followed by the number (e.g., 919360777089)
        $mobileNumber = preg_replace('/[^0-9]/', '', $user->mobile);
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
                                    "value" => $otp // Dynamically passes OTP to your message body
                                ],
                                "button_1" => [
                                    "subtype" => "url",
                                    "type"    => "text",
                                    "value"   => $otp // Passes OTP parameter for dynamic button URL suffixes if needed
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // Log response results for easy debugging
        if ($response->successful()) {
            Log::info("MSG91 WhatsApp Response Body: " . $response->body());
            return true;
        }

        Log::error("MSG91 WhatsApp API Error: " . $response->status() . " - " . $response->body());
        return false;
    }
}