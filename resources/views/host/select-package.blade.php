<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Wedding Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { wedding: { dark: '#1e3a2f', primary: '#2c5f41', gold: '#c4a373', cream: '#f9f7f4' } },
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Cormorant Garamond', 'serif'] }
                } 
            }
        }
    </script>
</head>
<body class="bg-stone-50 py-16 px-4 font-sans antialiased">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-serif text-wedding-dark mb-3">Choose Your Planning Suite</h2>
        <p class="text-stone-600 mb-12 text-lg max-w-xl mx-auto">Select a beautifully crafted package tailored perfectly to manage your guests, invitations, and wedding ceremonies.</p>

        <div class="max-w-xl mx-auto mb-10 text-left">
            @if ($errors->any() || session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl shadow-sm flex gap-3">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-sm">Action Required</h4>
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

        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8 items-stretch justify-center">
            @foreach($packages as $package)
            <div class="bg-white border border-stone-200 hover:border-wedding-gold transition-all duration-300 rounded-3xl p-6 md:p-8 flex flex-col shadow-sm hover:shadow-xl relative overflow-hidden group">
                
                <h3 class="text-xs font-bold text-wedding-gold uppercase tracking-widest mb-2">{{ $package->package_name }}</h3>
                
                <div class="flex items-baseline justify-center gap-1 mb-4">
                    <span class="text-4xl font-serif text-wedding-dark font-semibold">₹{{ number_format($package->price, 0) }}</span>
                    @if($package->validity)
                        <span class="text-stone-400 text-xs font-medium">/ Valid until {{ \Carbon\Carbon::parse($package->validity)->format('M d, Y') }}</span>
                    @endif
                </div>
                
                <p class="text-stone-500 text-sm mb-6 leading-relaxed min-h-[40px]">{{ $package->package_description }}</p>

                <div class="grid grid-cols-2 gap-3 bg-stone-50 rounded-2xl p-4 mb-6 border border-stone-100 text-left">
                    <div>
                        <span class="block text-[10px] font-bold tracking-wider text-stone-400 uppercase">Invites Allowed</span>
                        <span class="text-base font-semibold text-wedding-dark font-serif">{{ $package->invite_limit }} Invites</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold tracking-wider text-stone-400 uppercase">Guest Capacity</span>
                        <span class="text-base font-semibold text-wedding-dark font-serif">{{ $package->guest_limit }} Guests</span>
                    </div>
                </div>

                <div class="text-left border-t border-stone-100 pt-5 flex-grow mb-8">
                    <span class="block text-[11px] font-bold tracking-wider text-stone-400 uppercase mb-4">Included Features</span>
                    <ul class="space-y-3.5 text-sm">
                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">Invitation</strong>
                                <span class="text-stone-800">{{ $package->invitaion }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">RSVP Suite</strong>
                                <span class="text-stone-800">{{ $package->rsvp }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">Ceremonies</strong>
                                <span class="text-stone-800">{{ $package->ceramonies }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">Reports & Analytics</strong>
                                <span class="text-stone-800">{{ $package->reports }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">Gallery Access</strong>
                                <span class="text-stone-800">{{ $package->gallery }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-stone-700">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mt-0.5">✓</span>
                            <div>
                                <strong class="font-medium block text-xs text-stone-500 uppercase tracking-tight">VAF Allocation</strong>
                                <span class="text-stone-800">{{ $package->vaf }}</span>
                            </div>
                        </li>

                        @if(!empty($package->wishboard))
                        <li class="flex items-start gap-3 text-stone-700 border-t border-dashed border-stone-200 pt-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600 mt-0.5">✦</span>
                            <div>
                                <strong class="font-medium block text-xs text-amber-700 uppercase tracking-tight">Wishboard Option</strong>
                                <span class="text-stone-800">{{ $package->wishboard }}</span>
                            </div>
                        </li>
                        @endif

                        @if(!empty($package->dcgqrcode))
                        <li class="flex items-start gap-3 text-stone-700 border-t border-dashed border-stone-200 pt-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600 mt-0.5">✦</span>
                            <div>
                                <strong class="font-medium block text-xs text-amber-700 uppercase tracking-tight">DCG QR Access</strong>
                                <span class="text-stone-800">{{ $package->dcgqrcode }}</span>
                            </div>
                        </li>
                        @endif

                        <!-- Render Custom features cleanly -->
                        @if($package->customFeatures && $package->customFeatures->count() > 0)
                            @foreach($package->customFeatures as $feature)
                                <li class="flex items-start gap-3 text-stone-700 border-t border-dashed border-stone-200 pt-2">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-stone-100 text-wedding-gold mt-0.5">✦</span>
                                    <div>
                                        <strong class="font-medium block text-xs text-stone-400 uppercase tracking-tight">{{ $feature->field_label }}</strong>
                                        <span class="text-stone-800 font-semibold">
                                            @if($feature->field_type == 'price')
                                                ₹{{ number_format((float)$feature->field_value, 2) }}
                                            @elseif($feature->field_type == 'date')
                                                {{ \Carbon\Carbon::parse($feature->field_value)->format('M d, Y') }}
                                            @else
                                                {{ $feature->field_value }}
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <form action="{{ route('host.packages.select') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <button type="submit" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white py-3.5 rounded-xl font-semibold shadow-md transition-all tracking-wide text-sm">
                        Select Plan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>