<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1e40af">
    <title>Planner Login | Wedding Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        planner: {
                            dark: '#1e3a5f',      /* Deep Navy - header/button */
                            primary: '#2563eb',   /* Royal Blue - accents */
                            teal: '#0d9488',      /* Professional Teal - highlights */
                            light: '#f1f5f9',     /* Cool Gray - backgrounds */
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-br from-slate-50 via-planner-light to-slate-100 px-3 sm:px-4 py-4 sm:py-6">

<div class="w-full max-w-[440px] mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
        
        <!-- Header: Professional Planner Theme -->
        <div class="bg-planner-dark py-5 px-6 text-center relative">
            <div class="absolute inset-0 bg-gradient-to-r from-planner-primary/20 to-planner-teal/10"></div>
            <div class="relative">
                <div class="text-3xl mb-1">📋</div>
                <h4 class="text-2xl sm:text-3xl text-white font-serif font-semibold tracking-wide">Planner Login</h4>
                <p class="text-slate-200 text-sm mt-1.5">Wedding Management Portal</p>
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

            <form action="{{ route('planner.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-planner-primary focus:ring-2 focus:ring-planner-primary/30 transition placeholder-slate-400 text-base bg-white" 
                           placeholder="planner@weddings.com" 
                           required
                           autocomplete="email"
                           inputmode="email">
                    @error('email') 
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-planner-primary focus:ring-2 focus:ring-planner-primary/30 transition placeholder-slate-400 text-base bg-white" 
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
                        <input type="checkbox" name="remember" class="w-4 h-4 text-planner-primary border-slate-300 rounded focus:ring-planner-primary/30">
                        <span class="ml-2 text-sm text-slate-600">Remember this device</span>
                    </label>
                    <a href="#" class="text-sm text-planner-primary hover:text-planner-dark font-medium hover:underline transition text-center sm:text-right">
                        Reset password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-planner-dark hover:bg-planner-primary text-white font-medium py-3.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-planner-teal/50 focus:ring-offset-2 text-base flex items-center justify-center gap-2">
                    <span>Access Planner Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <!-- Professional Features Hint -->
            <div class="mt-5 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-planner-light flex items-center justify-center text-planner-primary mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-500">Schedule</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-planner-light flex items-center justify-center text-planner-primary mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-500">Guests</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-planner-light flex items-center justify-center text-planner-primary mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-500">Analytics</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-planner-light px-6 py-3.5 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-500">
                Professional access • <span class="text-planner-primary font-medium">WeddingPro Suite</span>
            </p>
        </div>
    </div>
    
   
</div>

</body>
</html>