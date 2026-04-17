<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Login | Wedding Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
        /* Decorative wedding pattern background */
        .bg-pattern {
            background-color: #fdfbf7;
            background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md transition-all duration-500">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-amber-100">
        
        <div class="bg-gradient-to-r from-amber-200 to-amber-100 p-8 text-center border-b border-amber-200">
            <h1 class="serif text-3xl text-amber-900 mb-2">Venue Portal</h1>
            <p class="text-amber-700 text-sm font-medium uppercase tracking-widest">Management Sign In</p>
        </div>

        <div class="p-8">
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('venue.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all placeholder-gray-400"
                           placeholder="manager@venue.com" required>
                    @error('email') 
                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-xs text-amber-700 hover:underline">Forgot?</a>
                    </div>
                    <input type="password" name="password" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all placeholder-gray-400"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" 
                        class="w-full bg-amber-800 hover:bg-amber-900 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transform active:scale-[0.98] transition-all">
                    Access Dashboard
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    Not a registered venue? <a href="#" class="text-amber-800 font-semibold hover:underline">Apply Here</a>
                </p>
            </div>
        </div>
    </div>
    
    <p class="text-center mt-6 text-gray-400 text-xs tracking-tighter uppercase">
        &copy; 2026 Wedding Project Management System
    </p>
</div>

</body>
</html>