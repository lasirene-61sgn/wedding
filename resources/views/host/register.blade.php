<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Register | Wedding Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        wedding: { 
                            dark: '#1e3a2f', 
                            primary: '#2c5f41', 
                            gold: '#c4a373', 
                            cream: '#f9f7f4' 
                        } 
                    } 
                } 
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h4 { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center bg-gradient-to-br from-stone-50 via-wedding-cream to-stone-100 px-3 py-10">

<div class="w-full max-w-[480px] mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-stone-200">
        
        <div class="bg-wedding-dark py-6 text-center">
            <h4 class="text-2xl text-white font-semibold tracking-wide">Host Registration</h4>
            <p class="text-stone-300 text-sm mt-1">Join us to manage your wedding</p>
        </div>

        <div class="p-6 sm:p-8">
            <a href="{{ route('host.google.login') }}" 
               class="w-full flex items-center justify-center gap-3 bg-white border border-stone-300 py-3 rounded-lg hover:bg-stone-50 transition-all shadow-sm">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
                <span class="text-sm font-semibold text-stone-700">Sign up with Google</span>
            </a>
            
            <div class="relative flex items-center my-6">
                <div class="flex-grow border-t border-stone-200"></div>
                <span class="text-stone-400 text-[10px] uppercase tracking-[0.2em] px-3">Using Email</span>
                <div class="flex-grow border-t border-stone-200"></div>
            </div>

            <form action="{{ route('host.register.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Default Sidebar Permissions (Hidden) --}}
                @php
                    $defaultModules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];
                @endphp
                @foreach($defaultModules as $module)
                    <input type="hidden" name="permissions[]" value="{{ Str::slug($module) }}">
                @endforeach

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none @error('name') border-red-500 @enderror" 
                           placeholder="Your Name" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Mobile Number</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" 
                           class="w-full px-4 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none @error('mobile') border-red-500 @enderror" 
                           placeholder="Your Number" required>
                    @error('mobile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none @error('email') border-red-500 @enderror" 
                           placeholder="email@example.com" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none @error('password') border-red-500 @enderror" 
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-2.5 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none" 
                               required>
                    </div>
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                <button type="submit" class="w-full bg-wedding-primary hover:bg-wedding-dark text-white font-medium py-3.5 rounded-lg shadow-md transition-all mt-4">
                    Create My Account
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-stone-500">
                Already have an account? 
                <a href="{{ route('host.login') }}" class="text-wedding-primary font-semibold hover:underline">Log in here</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>