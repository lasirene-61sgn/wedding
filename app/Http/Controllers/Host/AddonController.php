<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\ChannelAddon;
use App\Models\HostAddonPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class AddonController extends Controller
{
    public function index()
    {
        $host = Auth::user();

        $addons = ChannelAddon::active()->get()->groupBy('type');

        return view('host.addons.index', [
            'addons'        => $addons,
            'host'          => $host,
            'waEffective'   => $host->effectiveWhatsappLimit(),
            'smsEffective'  => $host->effectiveSmsLimit(),
            'emEffective'   => $host->effectiveEmailLimit(),
            'waSent'        => (int)($host->whatsapp_sent_count  ?? 0),
            'smsSent'       => (int)($host->sms_sent_count       ?? 0),
            'emSent'        => (int)($host->email_sent_count     ?? 0),
        ]);
    }

    /**
     * Create a Razorpay order for a chosen add-on.
     */
    public function initPayment(Request $request)
    {
        $host = Auth::user();
        if (!$host) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $request->validate(['addon_id' => 'required|exists:channel_addons,id']);

        $addon = ChannelAddon::findOrFail($request->addon_id);

        if (!$addon->is_active) {
            return response()->json(['error' => 'This add-on is no longer available.'], 400);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $order = $api->order->create([
                'receipt'         => 'addon_' . uniqid(),
                'amount'          => $addon->price * 100, // paise
                'currency'        => 'INR',
                'payment_capture' => 1,
            ]);

            // Log a pending purchase
            HostAddonPurchase::create([
                'host_id'           => $host->id,
                'addon_id'          => $addon->id,
                'razorpay_order_id' => $order['id'],
                'amount_paid'       => $addon->price,
                'status'            => 'pending',
            ]);

            return response()->json([
                'order_id'   => $order['id'],
                'amount'     => $addon->price * 100,
                'key'        => env('RAZORPAY_KEY'),
                'addon_name' => $addon->name,
                'host_name'  => $host->name  ?? '',
                'host_email' => $host->email ?? '',
            ]);
        } catch (\Exception $e) {
            Log::error('Addon Razorpay Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify payment and credit the host's addon limit.
     */
    public function purchase(Request $request)
    {
        $host = Auth::user();

        $request->validate([
            'addon_id'            => 'required|exists:channel_addons,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        // Verify Razorpay signature
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            // Mark purchase as failed
            HostAddonPurchase::where('razorpay_order_id', $request->razorpay_order_id)
                ->update(['status' => 'failed']);
            return redirect()->route('host.addons.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        $addon = ChannelAddon::findOrFail($request->addon_id);

        // Update purchase log
        HostAddonPurchase::where('razorpay_order_id', $request->razorpay_order_id)->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status'              => 'success',
        ]);

        // Credit the host's addon limit
        $field = match($addon->type) {
            'whatsapp' => 'whatsapp_addon_limit',
            'sms'      => 'sms_addon_limit',
            'email'    => 'email_addon_limit',
        };

        $host->increment($field, $addon->count);

        return redirect()->route('host.addons.index')
            ->with('success', "✅ Payment successful! {$addon->count} {$addon->name} credits added to your account.");
    }
}
