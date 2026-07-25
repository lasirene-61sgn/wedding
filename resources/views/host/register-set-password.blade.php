<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password | Wedding Registration</title>
    <!-- Tailwind CSS (via CDN if not compiled locally, assuming standard setup) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-xl w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        <!-- Header -->
        <div class="bg-blue-600 px-8 py-10 text-center text-white">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Secure Your Account</h2>
            <p class="text-blue-100 text-sm">Please set a secure password to complete your registration.</p>
        </div>

        <div class="p-8">
            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif

            <!-- Display Filled Details -->
            <div class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Your Registration Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500 mb-1">Name</span>
                        <span class="font-medium text-gray-900">{{ $sessionData['first_name'] ?? '' }} {{ $sessionData['last_name'] ?? '' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 mb-1">Email</span>
                        <span class="font-medium text-gray-900">{{ $sessionData['email'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 mb-1">Phone Number</span>
                        <span class="font-medium text-gray-900">{{ $sessionData['phone'] ?? $sessionData['mobile'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Password Form -->
            <form action="{{ route('host.register.set-password.submit') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        minlength="8"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none text-sm @error('password') border-red-500 @enderror"
                        placeholder="Enter a strong password"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none text-sm"
                        placeholder="Re-enter your password"
                    >
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition-colors shadow-lg shadow-blue-600/30">
                    Complete Registration
                </button>
            </form>
        </div>
    </div>

</body>
</html>
