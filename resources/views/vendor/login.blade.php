<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#7c2d12">
    <title>Vendor Login | Wedding Partners</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        vendor: {
                            dark: '#7c2d12',      /* Deep Amber - header/button */
                            primary: '#ea580c',   /* Vibrant Orange - accents */
                            warm: '#fcd34d',      /* Warm Amber - highlights */
                            light: '#fffbeb',     /* Soft Cream - backgrounds */
                        }
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-br from-stone-50 via-vendor-light to-stone-100 px-3 sm:px-4 py-4 sm:py-6">

<div class="w-full max-w-[440px] mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-stone-200">
        
        <!-- Header: Creative Vendor Theme -->
        <div class="bg-vendor-dark py-5 px-6 text-center relative">
            <div class="absolute inset-0 bg-gradient-to-r from-vendor-primary/20 to-vendor-warm/10"></div>
            <div class="relative">
                <div class="text-3xl mb-1">🎬</div>
                <h4 class="text-2xl sm:text-3xl text-white font-serif font-semibold tracking-wide">Vendor Login</h4>
                <p class="text-amber-100 text-sm mt-1.5">Wedding Partner Portal</p>
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-5 sm:p-7">
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm rounded-r">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded-r">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('vendor.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">Email Address</label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:border-vendor-primary focus:ring-2 focus:ring-vendor-primary/30 transition placeholder-stone-400 text-base bg-white" 
                           placeholder="vendor@services.com" 
                           required
                           autocomplete="email"
                           inputmode="email">
                    @error('email') 
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">Password</label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:border-vendor-primary focus:ring-2 focus:ring-vendor-primary/30 transition placeholder-stone-400 text-base bg-white" 
                           placeholder="••••••••" 
                           required
                           autocomplete="current-password">
                    @error('password') 
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-vendor-primary border-stone-300 rounded focus:ring-vendor-primary/30">
                        <span class="ml-2 text-sm text-stone-600">Stay signed in</span>
                    </label>
                    <a href="#" class="text-sm text-vendor-primary hover:text-vendor-dark font-medium hover:underline transition text-center sm:text-right">
                        Need help logging in?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-vendor-dark hover:bg-vendor-primary text-white font-medium py-3.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-vendor-warm/50 focus:ring-offset-2 text-base flex items-center justify-center gap-2">
                    <span>Access Vendor Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <!-- Vendor Services Preview -->
            <div class="mt-5 pt-4 border-t border-stone-100">
                <p class="text-xs text-stone-500 text-center mb-3">Manage your wedding services:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-2.5 py-1 bg-vendor-light text-vendor-dark text-[10px] font-medium rounded-full border border-amber-200">📸 Photography</span>
                    <span class="px-2.5 py-1 bg-vendor-light text-vendor-dark text-[10px] font-medium rounded-full border border-amber-200">🍽️ Catering</span>
                    <span class="px-2.5 py-1 bg-vendor-light text-vendor-dark text-[10px] font-medium rounded-full border border-amber-200">💐 Florals</span>
                    <span class="px-2.5 py-1 bg-vendor-light text-vendor-dark text-[10px] font-medium rounded-full border border-amber-200">🎵 Music</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-vendor-light px-6 py-3.5 border-t border-stone-100 text-center">
            <p class="text-xs text-stone-500">
                Partner access • <span class="text-vendor-primary font-medium">WeddingPro Connect</span>
            </p>
        </div>
    </div>
    
</div>

</body>
</html>