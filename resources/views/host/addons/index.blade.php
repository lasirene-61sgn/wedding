@extends('layouts.host')

@section('content')
<div class="main-container" style="padding: 20px; font-family: 'Inter', sans-serif; background: #fbfcfe;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0;">Message Add-ons</h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Top up your credits for WhatsApp, SMS, and Email campaigns.</p>
        </div>
        <a href="{{ route('host.guestlist.index') }}" style="background: #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
            Back to Guestlist
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; font-size: 14px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Current Quota Summary -->
    <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">Your Current Limits (Base + Addons)</h3>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 13px; font-weight: 700; color: #15803d; margin-bottom: 5px;">📲 WhatsApp Limit</div>
                <div style="font-size: 20px; font-weight: 800; color: #1e293b;">
                    {{ $waEffective > 0 ? $waSent . ' / ' . $waEffective : 'Unlimited' }}
                </div>
            </div>
            
            <div style="flex: 1; min-width: 200px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 13px; font-weight: 700; color: #a16207; margin-bottom: 5px;">💬 SMS Limit</div>
                <div style="font-size: 20px; font-weight: 800; color: #1e293b;">
                    {{ $smsEffective > 0 ? $smsSent . ' / ' . $smsEffective : 'Unlimited' }}
                </div>
            </div>

            <div style="flex: 1; min-width: 200px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 13px; font-weight: 700; color: #1d4ed8; margin-bottom: 5px;">✉️ Email Limit</div>
                <div style="font-size: 20px; font-weight: 800; color: #1e293b;">
                    {{ $emEffective > 0 ? $emSent . ' / ' . $emEffective : 'Unlimited' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Available Add-ons -->
    @php
        $typeConfig = [
            'whatsapp' => ['label' => 'WhatsApp', 'color' => '#22c55e', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
            'sms'      => ['label' => 'SMS',      'color' => '#eab308', 'bg' => '#fefce8', 'border' => '#fde68a'],
            'email'    => ['label' => 'Email',    'color' => '#3b82f6', 'bg' => '#eff6ff', 'border' => '#bfdbfe'],
        ];
    @endphp

    @foreach(['whatsapp', 'sms', 'email'] as $type)
        @php 
            $channelAddons = $addons->get($type, collect()); 
            $cfg = $typeConfig[$type];
        @endphp
        
        <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 15px; margin-top: 30px;">{{ $cfg['label'] }} Packs</h3>
        
        @if($channelAddons->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                @foreach($channelAddons as $addon)
                    <div style="background: white; border: 2px solid {{ $cfg['border'] }}; border-radius: 16px; padding: 20px; transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden;" class="hover-card">
                        
                        <!-- Top Accent Bar -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: {{ $cfg['color'] }};"></div>
                        
                        <div style="font-size: 18px; font-weight: 800; color: #1e293b; margin-top: 5px;">{{ $addon->name }}</div>
                        
                        <div style="margin: 20px 0; padding: 15px; background: {{ $cfg['bg'] }}; border-radius: 12px; text-align: center;">
                            <span style="font-size: 28px; font-weight: 900; color: {{ $cfg['color'] }};">+{{ number_format($addon->count) }}</span>
                            <span style="font-size: 13px; font-weight: 700; color: #475569; display: block; text-transform: uppercase; margin-top: 4px;">Credits</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                            <div style="font-size: 22px; font-weight: 800; color: #1e293b;">₹{{ number_format($addon->price) }}</div>
                            <button onclick="buyAddon({{ $addon->id }}, '{{ $addon->name }}', {{ $addon->price }})" 
                                    style="background: {{ $cfg['color'] }}; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                Buy Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: white; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 30px; text-align: center; color: #64748b; font-size: 14px;">
                No {{ $cfg['label'] }} add-on packs are available at the moment.
            </div>
        @endif
    @endforeach

</div>

<!-- Hidden form to process successful payment -->
<form id="purchase-form" action="{{ route('host.addons.purchase') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="addon_id" id="form_addon_id">
    <input type="hidden" name="razorpay_order_id" id="form_razorpay_order_id">
    <input type="hidden" name="razorpay_payment_id" id="form_razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="form_razorpay_signature">
</form>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    }
</style>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function buyAddon(addonId, addonName, price) {
        if(!confirm(`Proceed to buy ${addonName} for ₹${price}?`)) {
            return;
        }

        // Init payment request
        fetch("{{ route('host.addons.initPayment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ addon_id: addonId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                alert(data.error);
                return;
            }

            var options = {
                "key": data.key,
                "amount": data.amount, // in paise
                "currency": "INR",
                "name": "Wedding Application",
                "description": "Purchase Add-on: " + data.addon_name,
                "order_id": data.order_id,
                "handler": function (response) {
                    // Populate hidden form and submit
                    document.getElementById('form_addon_id').value = addonId;
                    document.getElementById('form_razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('form_razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('form_razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('purchase-form').submit();
                },
                "prefill": {
                    "name": data.host_name,
                    "email": data.host_email
                },
                "theme": {
                    "color": "#4f46e5"
                }
            };
            
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function (response){
                alert("Payment Failed: " + response.error.description);
            });
            rzp1.open();
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong initiating the payment.');
        });
    }
</script>
@endsection
