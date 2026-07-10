<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1e3a2f">
    <title>Host Login | Wedding Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wedding: {
                            dark: '#1e3a2f',      /* Deep Forest */
                            primary: '#2c5f41',   /* Sage Green */
                            gold: '#c4a373',      /* Elegant Gold */
                            cream: '#f9f7f4',     /* Soft Cream */
                        }
                    },
                    fontFamily: {
                        serif: ['Cormorant Garamond', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-br from-stone-50 via-wedding-cream to-stone-100 px-3 py-6">

<div class="w-full max-w-[440px] mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-stone-200">
        
        <div class="bg-wedding-dark py-5 px-6 text-center relative">
            <div class="absolute inset-0 bg-gradient-to-r from-wedding-primary/20 to-wedding-gold/10"></div>
            <div class="relative">
                <div class="text-3xl mb-1">👰🤵</div>
                <h4 class="text-2xl sm:text-3xl text-white font-serif font-semibold tracking-wide">Host Login</h4>
                <p class="text-stone-200 text-sm mt-1.5">Bride & Groom Dashboard</p>
            </div>
        </div>

        <div class="p-5 sm:p-7">
            
            {{-- 1. Catch Custom Session Errors (e.g., Auth failure) --}}
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm rounded-r">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- 2. Catch Standard Validation Errors Bag --}}
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm rounded-r">
                    <ul class="list-disc list-inside font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded-r">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('host.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">Email Address</label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-lg border @error('email') border-red-400 focus:border-red-400 focus:ring-red-400/30 @else border-stone-300 focus:border-wedding-primary focus:ring-wedding-primary/30 @enderror transition placeholder-stone-400 text-base bg-white" 
                           placeholder="host@wedding.com" 
                           required>
                    @error('email') 
                        <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">Password</label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           class="w-full px-4 py-3 rounded-lg border @error('password') border-red-400 focus:border-red-400 focus:ring-red-400/30 @else border-stone-300 focus:border-wedding-primary focus:ring-wedding-primary/30 @enderror transition placeholder-stone-400 text-base bg-white" 
                           placeholder="••••••••" 
                           required>
                    @error('password') 
                        <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-wedding-primary border-stone-300 rounded focus:ring-wedding-primary/30">
                        <span class="ml-2 text-sm text-stone-600 font-sans">Keep me signed in</span>
                    </label>
                    <a href="{{ route('host.password.request') }}" class="text-sm text-wedding-primary hover:text-wedding-dark font-medium hover:underline transition font-sans">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" 
                        class="w-full bg-wedding-dark hover:bg-wedding-primary text-white font-medium py-3.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98] focus:outline-none flex items-center justify-center gap-2 font-sans">
                    <span>Access Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-100 text-center">
                <p class="text-stone-600 text-sm font-sans">
                    Don't have an account yet? 
                    <a href="{{ route('host.register') }}" class="text-wedding-primary font-bold hover:text-wedding-dark transition ml-1 underline underline-offset-4 decoration-wedding-gold/40 hover:decoration-wedding-primary">
                        Create Host Account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>