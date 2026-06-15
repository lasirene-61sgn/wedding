<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Mobile | Wedding Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght=600&family=Inter:wght=400;500&display=swap" rel="stylesheet">
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

<div class="w-full max-w-[450px] mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-stone-200">
        
        <div class="bg-wedding-dark py-6 text-center">
            <h4 class="text-2xl text-white font-semibold tracking-wide">WhatsApp Verification</h4>
            <p class="text-stone-300 text-sm mt-1">We sent a security code to your mobile</p>
        </div>

        <div class="p-6 sm:p-8">
            {{-- Status Messages --}}
            @if(session('error'))
                <div class="mb-4 p-3.5 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('host.verify.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-2 text-center">
                        Enter the 6-digit verification code
                    </label>
                    <input type="text" 
                           name="otp" 
                           maxlength="6"
                           placeholder="· · · · · ·" 
                           class="w-full text-center text-2xl tracking-[0.4em] font-semibold px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-wedding-primary/30 outline-none transition-all placeholder:text-stone-300 placeholder:tracking-normal" 
                           required 
                           autocomplete="off">
                    @error('otp') 
                        <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p> 
                    @enderror
                </div>

                <button type="submit" class="w-full bg-wedding-primary hover:bg-wedding-dark text-white font-medium py-3.5 rounded-lg shadow-md transition-all mt-2">
                    Confirm & Complete Setup
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-stone-500 border-t border-stone-100 pt-4">
                Didn't get the code? 
                <a href="{{ route('host.register') }}" class="text-wedding-primary font-semibold hover:underline">Restart Registration</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>