<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select Your Wedding Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wedding: {
                            dark: '#000000',
                            primary: '#222222',
                            gold: '#c4a373',
                            cream: '#f9f7f4'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-stone-50 py-16 px-4 font-sans antialiased text-black">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-12">Happywed - Host Packages</h2>

        <!-- Notification Alerts -->
        <div class="max-w-xl mx-auto mb-10 text-left">
            @if ($errors->any() || session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl shadow-sm flex gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <ul class="list-disc pl-4 text-xs text-red-700 mt-1 space-y-1">
                        @if(session('error')) <li>{{ session('error') }}</li> @endif
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm flex gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
            @endif
        </div>

        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 items-stretch justify-center">
            @foreach($packages as $package)
            <div class="bg-white border-2 border-gray-300 rounded-xl p-5 flex flex-col shadow-sm transition-all duration-300 hover:shadow-md relative text-left">

                <!-- 1. Package Name Data -->
                <h3 class="text-xl font-bold text-black border-b border-gray-200 pb-2 mb-3">{{ $package->package_name }}</h3>

                <!-- 2. Price Data -->
                <div class="flex items-baseline gap-2 mb-3 flex-wrap text-lg">
                    @php
                    $priceParts = explode(' ', trim($package->price));
                    @endphp

                    @if(count($priceParts) > 1 && is_numeric($priceParts[0]))
                    <span class="font-bold text-gray-950 line-through">₹{{ $priceParts[0] }}</span>
                    <span class="font-bold text-gray-950">
                        {{ implode(' ', array_slice($priceParts, 1)) }}
                    </span>
                    @else
                    <span class="font-bold text-gray-950">₹{{ $package->price }}</span>
                    @endif
                </div>

                <!-- 3. Guest Limit Data -->
                <div class="text-base font-bold text-black mb-2">
                    Guest up to {{ $package->guest_limit }}
                </div>

                <!-- 4. Validity Data (Calculated dynamically into years format instead of explicit calendar terms) -->
                <div class="text-sm text-gray-900 mb-4 font-normal">
                    <li class="flex items-start gap-2">
                        <span class="text-gray-400 font-bold shrink-0">+</span>
                        <span class="leading-tight">{!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->validity)) !!}</span>
                    </li>
                </div>

                <!-- Core Data Stack Container -->
                <div class="border-t border-gray-200 pt-4 flex-grow mb-6">
                    <ul class="space-y-3.5 text-sm text-gray-900 font-medium">
                        <!-- 5. Invitation Data -->
                        <li class="flex items-start gap-2">
                            <span class="text-gray-400 font-bold shrink-0"></span>
                            <span class="leading-tight">{!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->invitaion)) !!}</span>
                        </li>

                        <!-- 6. RSVP Data -->
                        <li class="pl-4 leading-tight">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->rsvp)) !!}
                        </li>

                        <!-- 7. Ceremonies Data -->
                        <li class="pl-4 leading-tight">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->ceramonies)) !!}
                        </li>

                        <!-- 8. Reports Data -->
                        <li class="pl-4 leading-tight">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->reports)) !!}
                        </li>

                        <!-- 9. Gallery Data -->
                        <li class="pl-4 leading-tight">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->gallery)) !!}
                        </li>

                        <!-- 10. Package Description Data -->
                        <li class="pl-4 leading-normal text-xs text-gray-800 border-t border-gray-100 pt-2 font-normal">
                            {!! preg_replace('/(\d+|Upto|Free|Rs\.\/Per Msg\.)/i', '<span class="font-bold">$1</span>', e($package->package_description)) !!}
                        </li>

                        <!-- 11. Wishboard Data -->
                        @if(!empty($package->wishboard))
                        <li class="pl-4 leading-tight text-gray-900 border-t border-gray-100 pt-2">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->wishboard)) !!}
                        </li>
                        @endif

                        <!-- 12. DCG QR Code Data -->
                        @if(!empty($package->dcgqrcode))
                        <li class="pl-4 leading-tight text-gray-900 border-t border-gray-100 pt-1">
                            {!! preg_replace('/\((.*?)\)/', '<span class="font-bold">($1)</span>', e($package->dcgqrcode)) !!}
                        </li>
                        @endif

                        <!-- 13. VAF Data -->
                        <li class="pl-4 leading-tight text-xs text-gray-600 border-t border-gray-100 pt-2 font-normal">
                            {{ $package->vaf }}
                        </li>

                        <!-- 14. Dynamic Custom Fields Data -->
                        @if($package->customFeatures && $package->customFeatures->count() > 0)
                        @foreach($package->customFeatures as $feature)
                        <li class="pl-4 leading-tight text-xs border-t border-dashed border-gray-200 pt-2 font-normal">
                            <span>
                                @if($feature->field_type == 'price')
                                ₹{{ $feature->field_value }}
                                @elseif($feature->field_type == 'date')
                                {{ \Carbon\Carbon::parse($feature->field_value)->format('M d, Y') }}
                                @else
                                {{ $feature->field_value }}
                                @endif
                            </span>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Form Action Selection Button -->
                <form action="{{ route('host.register.packages.select') }}" method="POST" class="mt-auto package-form" data-package-id="{{ $package->id }}">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="razorpay_payment_id" class="rzp_payment_id">
                    <input type="hidden" name="razorpay_order_id" class="rzp_order_id">
                    <input type="hidden" name="razorpay_signature" class="rzp_signature">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-bold text-center transition-all text-sm tracking-wide cursor-pointer shadow-sm select-plan-btn">
                        Select Plan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.package-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const packageId = this.dataset.packageId;
                const submitBtn = this.querySelector('.select-plan-btn');
                const originalText = submitBtn.innerText;
                
                submitBtn.innerText = 'Processing...';
                submitBtn.disabled = true;

                try {
                    const response = await fetch("{{ route('host.register.initPayment') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ package_id: packageId })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (data.error === 'Invalid package price for payment') {
                            // Free package or error in price parsing, just submit the form normally
                            this.submit();
                            return;
                        }
                        alert(data.error || 'Something went wrong while initiating payment.');
                        submitBtn.innerText = originalText;
                        submitBtn.disabled = false;
                        return;
                    }

                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": "INR",
                        "name": "Happywed",
                        "description": "Payment for " + data.package_name,
                        "order_id": data.order_id,
                        "handler": function (response){
                            form.querySelector('.rzp_payment_id').value = response.razorpay_payment_id;
                            form.querySelector('.rzp_order_id').value = response.razorpay_order_id;
                            form.querySelector('.rzp_signature').value = response.razorpay_signature;
                            form.submit();
                        },
                        "prefill": {
                            "name": data.host_name,
                            "email": data.host_email
                        },
                        "theme": {
                            "color": "#4f46e5"
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.on('payment.failed', function (response){
                        alert(response.error.description);
                        submitBtn.innerText = originalText;
                        submitBtn.disabled = false;
                    });
                    rzp.open();
                    
                } catch (error) {
                    console.error("Payment error: ", error);
                    alert("Error initiating payment.");
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
</body>

</html>