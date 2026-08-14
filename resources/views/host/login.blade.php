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

            <form id="loginForm" action="{{ route('host.login.submit') }}" method="POST" class="space-y-5">
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
                    <div class="relative">
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="w-full pl-4 pr-11 py-3 rounded-lg border @error('password') border-red-400 focus:border-red-400 focus:ring-red-400/30 @else border-stone-300 focus:border-wedding-primary focus:ring-wedding-primary/30 @enderror transition placeholder-stone-400 text-base bg-white" 
                               placeholder="••••••••" 
                               required>
                        
                        {{-- Password Toggle Button --}}
                        <button type="button" 
                                id="togglePasswordBtn" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none"
                                aria-label="Toggle password visibility">
                            <!-- Eye Open Icon -->
                            <svg id="eyeOpenIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Closed Icon -->
                            <svg id="eyeClosedIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
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

                {{-- Submit Button with Loading Spinner --}}
                <button type="submit" 
                        id="submitBtn"
                        class="w-full bg-wedding-dark hover:bg-wedding-primary disabled:opacity-75 disabled:cursor-not-allowed text-white font-medium py-3.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98] focus:outline-none flex items-center justify-center gap-2 font-sans">
                    
                    {{-- Spinner (hidden by default) --}}
                    <svg id="loadingSpinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>

                    <span id="btnText">Access Dashboard</span>
                    
                    {{-- Default Arrow Icon --}}
                    <svg id="btnArrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

<script>
    // 1. Password Visibility Toggle
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpenIcon');
        const eyeClosed = document.getElementById('eyeClosedIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // 2. Form Loading State on Submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnArrow = document.getElementById('btnArrow');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Disable button & show spinner
        submitBtn.disabled = true;
        btnText.textContent = 'Accessing Dashboard...';
        btnArrow.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
    });
</script>

</body>
</html>