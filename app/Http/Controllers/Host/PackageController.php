<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('host.select-package', compact('packages'));
    }

    public function initPayment(Request $request)
    {
        $host = Auth::guard('host')->user();
        if (!$host) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        $package = Package::find($request->package_id);
        
        // Extract numeric price
        $priceParts = explode(' ', trim($package->price));
        $activePriceStr = count($priceParts) > 1 && is_numeric($priceParts[0]) ? implode(' ', array_slice($priceParts, 1)) : $package->price;
        $numericPrice = (int) preg_replace('/[^0-9]/', '', $activePriceStr);

        if ($numericPrice <= 0) {
            return response()->json(['error' => 'Invalid package price for payment'], 400);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $order = $api->order->create([
                'receipt'         => 'order_rcptid_' . uniqid(),
                'amount'          => $numericPrice * 100, // amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ]);

            return response()->json([
                'order_id' => $order['id'],
                'amount'   => $numericPrice * 100,
                'key'      => env('RAZORPAY_KEY'),
                'package_name' => $package->package_name,
                'host_name' => $host->name ?? '',
                'host_email' => $host->email ?? ''
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay Order Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function select(Request $request)
    {
        // 1. Check authentication FIRST before validating input data
        $host = Auth::guard('host')->user();

        if (!$host) {
            return redirect()->route('host.login')->with('error', 'Your session expired. Please login again.');
        }

        try {
            // 2. Validate incoming request parameters
            $request->validate([
                'package_id' => 'required|exists:packages,id',
                'razorpay_payment_id' => 'nullable|string',
                'razorpay_order_id' => 'nullable|string',
                'razorpay_signature' => 'nullable|string',
            ]);

            $package = Package::find($request->package_id);
            $priceParts = explode(' ', trim($package->price));
            $activePriceStr = count($priceParts) > 1 && is_numeric($priceParts[0]) ? implode(' ', array_slice($priceParts, 1)) : $package->price;
            $numericPrice = (int) preg_replace('/[^0-9]/', '', $activePriceStr);

            // Verify payment if price is > 0
            if ($numericPrice > 0) {
                if (!$request->razorpay_payment_id || !$request->razorpay_signature) {
                    return redirect()->back()->with('error', 'Payment details are missing.');
                }

                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                try {
                    $attributes = array(
                        'razorpay_order_id' => $request->razorpay_order_id,
                        'razorpay_payment_id' => $request->razorpay_payment_id,
                        'razorpay_signature' => $request->razorpay_signature
                    );
                    $api->utility->verifyPaymentSignature($attributes);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Payment verification failed: ' . $e->getMessage());
                }
            }

            // 3. Update host credentials
            $host->update([
                'package_id'     => $request->package_id,
                'package_status' => 'active',
            ]);

            // 4. Redirect onward to the wizard workflow
            return redirect()->route('host.wizard.index')->with('success', "Package Activated! Let's set up your wedding info.");

        } catch (ValidationException $e) {
            // Let Laravel handle standard validation rules naturally
            throw $e;
        } catch (\Exception $e) {
            // Catch actual code, database, or connection breakages
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}