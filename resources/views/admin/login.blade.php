<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Wedding Project</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    // 🎨 BRAND COLOR PALETTE (Orange/Gold Theme)
                    colors: {
                        'brand-orange': '#ff6b35',        // Primary brand orange
                        'brand-orange-light': '#ff8c5a',   // Lighter orange
                        'brand-gold': '#d4af37',           // Elegant gold accent
                        'brand-gold-light': '#e6c973',     // Soft gold
                        'brand-cream': '#fff9f5',          // Warm cream background
                        'brand-charcoal': '#2d2d2d',       // Dark text
                        'brand-gray': '#6b7280',           // Secondary text
                    },
                    fontFamily: {
                        'serif': ['Playfair Display', 'serif'],
                        'sans': ['Inter', 'sans-serif'],
                    },
                    // 🌀 3D EFFECTS CONFIGURATION
                    boxShadow: {
                        '3d': '0 10px 30px -5px rgba(0,0,0,0.15), 0 5px 15px -3px rgba(212,175,55,0.2), inset 0 1px 0 rgba(255,255,255,0.4)',
                        '3d-hover': '0 20px 40px -8px rgba(0,0,0,0.25), 0 10px 25px -5px rgba(212,175,55,0.35), inset 0 1px 0 rgba(255,255,255,0.6)',
                        'inner-glow': 'inset 0 2px 6px rgba(255,107,53,0.15)',
                        'glow-orange': '0 0 20px rgba(255,107,53,0.25)',
                    },
                    // 🔄 3D TRANSFORM UTILITIES
                    transformStyle: {
                        '3d': 'preserve-3d',
                    },
                    perspective: {
                        '3d': '1000px',
                        '3d-deep': '500px',
                    },
                    rotate: {
                        'x-1': 'rotateX(1deg)',
                        'x-2': 'rotateX(2deg)',
                        'y-1': 'rotateY(1deg)',
                        'y-2': 'rotateY(2deg)',
                    },
                    // ✨ ANIMATIONS
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 1.5s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4,0,0.6,1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-cream via-white to-orange-50 flex items-center justify-center p-3 sm:p-4 md:p-6 font-sans relative overflow-hidden">

    <!-- 🎭 3D Floating Decorative Elements -->
    <div class="absolute top-8 left-8 w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-brand-orange/30 to-brand-gold-light/30 rounded-full blur-2xl opacity-40 animate-float"></div>
    <div class="absolute bottom-20 right-6 sm:right-20 w-24 h-24 sm:w-36 sm:h-36 bg-gradient-to-br from-brand-gold/40 to-brand-orange-light/30 rounded-full blur-3xl opacity-35 animate-float-delayed"></div>
    <div class="absolute top-1/3 right-10 w-14 h-14 bg-brand-orange/20 rounded-full blur-xl opacity-25 animate-pulse-slow"></div>
    <div class="absolute bottom-1/4 left-6 sm:left-12 w-16 h-16 bg-brand-gold/25 rounded-full blur-xl opacity-20"></div>

    <div class="w-full max-w-md mx-2 sm:mx-4 perspective-3d">
        
        <!-- 🌹 Elegant 3D Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-brand-orange-light/20 to-brand-gold-light/30 mb-4 shadow-3d hover:shadow-glow-orange transition-all duration-500 transform hover:scale-110">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-brand-gold drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-brand-charcoal drop-shadow-sm">Admin Portal</h1>
            <p class="text-brand-gray mt-2 text-xs sm:text-sm">Wedding Management System</p>
        </div>

        <!-- 🎴 3D Login Card with Depth Effect -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl sm:rounded-3xl shadow-3d hover:shadow-3d-hover border border-brand-orange/10 overflow-hidden transition-all duration-500 transform hover:-translate-y-1.5">
            
            <!-- ✨ Gradient Top Border with Glow -->
            <div class="h-1.5 bg-gradient-to-r from-brand-orange via-brand-gold to-brand-orange-light shadow-inner-glow"></div>
            
            <div class="p-5 sm:p-7 md:p-8">
                <!-- Error Alert -->
                @if(session('error'))
                    <div class="mb-5 p-4 bg-red-50/80 border-l-4 border-red-400 text-red-700 rounded-r-lg flex items-start gap-3 shadow-sm backdrop-blur-sm" role="alert">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-brand-charcoal mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-brand-gray/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-brand-charcoal placeholder-brand-gray/50 focus:outline-none focus:ring-2 focus:ring-brand-orange focus:border-brand-orange focus:shadow-inner-glow transition-all duration-300 @error('email') border-red-300 bg-red-50 @enderror"
                                placeholder="admin@example.com" 
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                        </div>
                        @error('email') 
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-brand-charcoal mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-brand-gray/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-brand-charcoal placeholder-brand-gray/50 focus:outline-none focus:ring-2 focus:ring-brand-orange focus:border-brand-orange focus:shadow-inner-glow transition-all duration-300"
                                placeholder="••••••••" 
                                required
                                autocomplete="current-password"
                            >
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-brand-orange border-gray-300 rounded focus:ring-brand-orange focus:ring-offset-0 focus:ring-2">
                            <span class="text-brand-gray group-hover:text-brand-charcoal transition-colors">Remember me</span>
                        </label>
                        <a href="#" class="text-brand-orange hover:text-brand-orange-light font-medium transition-colors hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- 🎯 3D Submit Button with Depth -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-brand-orange to-brand-gold hover:from-brand-orange-light hover:to-brand-gold-light text-white font-semibold py-3 px-6 rounded-xl shadow-3d hover:shadow-3d-hover transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-orange focus:ring-offset-white"
                    >
                        <span class="flex items-center justify-center gap-2">
                            Sign In to Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </button>
                </form>

                <!-- Decorative Divider -->
                <div class="relative my-6 sm:my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200/60"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white/95 text-brand-gray/40">
                            <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/></svg>
                        </span>
                    </div>
                </div>

                <!-- Footer -->
                <p class="text-center text-xs text-brand-gray/50">
                    &copy; {{ date('Y') }} Wedding Bliss Admin. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Responsive Helper Text -->
        <p class="text-center text-xs text-brand-gray/40 mt-4 sm:mt-6 hidden sm:block">
            ✨ Fully Responsive • Secure Admin Access
        </p>
    </div>

</body>
</html>