<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Models\OtpVerification;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{


    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function __construct(
        protected PasswordResetService $resetService
    ) {}

    public function showVerifyForm(Request $request)
    {
        $identifier = $request->query('identifier');

        return view('auth.verify-otp', compact('identifier'));
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'channel' => 'required|in:email,sms,whatsapp',
        ]);

        try {
            $this->resetService->sendotp(
                $request->identifier,
                $request->channel,
            );
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Have Been Sent To your ' . $request->channel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyotp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp' => 'required',
        ]);

        $verification = OtpVerification::where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->first();

        if (!$verification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP',
            ], 422);
        }

        if (Carbon::parse($verification->expires_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP Has been Expired',
            ], 422);
        }

        $verification->update([
            'verified_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP Verified SucessFully',
            'token' => $verification->token,
        ]);
    }

    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token');
        $verification = OtpVerification::where('token', $token)->whereNotNull('verified_at')->first();

        if (!$verification) {
            return redirect()->route('host.password.request')->with('error', 'Invalid or expired token');
        }
        return view('auth.reset-password', compact('token'));
    }

    public function updatePassword(Request $request)
    {
        try {
            // 1. Manual validation handling to prevent automatic redirects
            $validator = Validator::make($request->all(), [
                'token'    => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // 2. Fetch the verification row
            $verification = OtpVerification::where('token', $request->token)
                ->whereNotNull('verified_at')
                ->first();

            if (!$verification) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or expired token. Please restart the process.'
                ], 422);
            }

            // 3. Locate the Host record
            $user = Host::where('email', $verification->identifier)
                ->orWhere('mobile', $verification->identifier)
                ->first();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Associated host account could not be found.'
                ], 422);
            }

            // 4. Perform the update with explicit Facade reference
            $user->password = Hash::make($request->password);
        $user->save();

            // 5. Clean up the used token
            \Illuminate\Support\Facades\DB::table('otp_verifications')
                ->where('token', $request->token)
                ->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Your password has been updated successfully!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Internal Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
