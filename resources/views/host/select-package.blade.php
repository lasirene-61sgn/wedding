<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Wedding Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { wedding: { dark: '#1e3a2f', primary: '#2c5f41', gold: '#c4a373', cream: '#f9f7f4' } } } }
        }
    </script>
</head>
<body class="bg-stone-50 py-16 px-4 font-sans">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-4xl font-serif text-wedding-dark mb-3">Choose Your Planning Suite</h2>
        <p class="text-stone-600 mb-12 text-lg">Select a package to start managing your guests and ceremonies.</p>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($packages as $package)
            <div class="bg-white border-2 border-stone-100 hover:border-wedding-gold transition-all duration-300 rounded-3xl p-8 flex flex-col shadow-sm hover:shadow-xl relative overflow-hidden group">
                
                <h3 class="text-xl font-bold text-wedding-dark uppercase tracking-widest mb-2">{{ $package->package_name }}</h3>
                <div class="text-4xl font-serif text-wedding-primary font-semibold mb-4">
                    ₹{{ number_format($package->price, 0) }}
                </div>
                
                <p class="text-stone-500 text-sm mb-6 leading-relaxed">
                    {{ $package->package_description }}
                </p>

                <ul class="text-left space-y-4 mb-10 flex-grow border-t border-stone-50 pt-6">
                    <li class="flex items-center gap-3 text-stone-700 font-medium">
                        <svg class="w-5 h-5 text-wedding-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        {{ $package->invite_limit }} Digital Invites
                    </li>
                    <li class="flex items-center gap-3 text-stone-700 font-medium">
                        <svg class="w-5 h-5 text-wedding-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        {{ $package->guest_limit }} Guest List Capacity
                    </li>
                </ul>

                <form action="{{ route('host.packages.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <button type="submit" class="w-full bg-wedding-dark group-hover:bg-wedding-primary text-white py-4 rounded-xl font-semibold shadow-lg transition-all active:scale-[0.98]">
                        Select This Plan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>