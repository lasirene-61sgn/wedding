<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpServices
{
    public function generateAndSend($user, string $channel){
        $otp = (string) rand(100000, 999999);
        $token = Str::random(60);
        $identifier = ($channel === 'email') ? $user->email : $user->mobile;

        OtpVerification::where('identifier', $identifier)->delete();

        OtpVerification::create([
            'identifier' => $identifier,
            'otp' => $otp,
            'token' => $token,
            'expires_at' => now()->addMinutes(15),
        ]);

        if($channel === 'email'){
            $this->sendEmail($user, $otp);
        }else{
            $this->sendWhatsapp($user, $otp);
        }

        return $token;
    }

    protected function sendEmail($user, $otp){

    }

    protected function sendWhatsapp($user, $otp){
        
    }
}
