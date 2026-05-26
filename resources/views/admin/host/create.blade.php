@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto mb-12">
    <!-- Header Area -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Create New Host</h1>
        <p class="text-sm text-gray-500 mt-1">Provision a new standalone host account and allocate platform privileges.</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-700">Host Management Form</h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.host.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section: Basic Information -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        
                        <!-- Full Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="w-full px-3.5 py-2 border rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-gray-200' }}">
                            @error('name') 
                                <span class="text-xs font-medium text-rose-500 mt-0.5"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="w-full px-3.5 py-2 border rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-gray-200' }}">
                            @error('email') 
                                <span class="text-xs font-medium text-rose-500 mt-0.5"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Primary Mobile -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Primary Mobile</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" 
                                class="w-full px-3.5 py-2 border rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ $errors->has('mobile') ? 'border-rose-400 bg-rose-50/30' : 'border-gray-200' }}">
                            @error('mobile') 
                                <span class="text-xs font-medium text-rose-500 mt-0.5"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Alternate Number -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Alternate Number</label>
                            <input type="text" name="alternate_number" value="{{ old('alternate_number') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- Account Password -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Account Password</label>
                            <input type="password" name="password" 
                                class="w-full px-3.5 py-2 border rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-gray-200' }}">
                            @error('password') 
                                <span class="text-xs font-medium text-rose-500 mt-0.5"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</span> 
                            @enderror
                        </div>

                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Section: Address & Profile Details -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-4">Address & Profile Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                        
                        <!-- Pincode -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Pincode</label>
                            <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" maxlength="6"
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- Complex Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Complex Name</label>
                            <input type="text" name="complex_name" value="{{ old('complex_name') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- Floor / Door No (Combined Input Group) -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Floor / Door No</label>
                            <div class="flex rounded-xl border border-gray-200 divide-x divide-gray-200 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 overflow-hidden transition-all">
                                <input type="text" name="floor" placeholder="Floor" value="{{ old('floor') }}" 
                                    class="w-1/2 px-3.5 py-2 text-gray-800 placeholder-gray-400 focus:outline-none bg-transparent">
                                <input type="text" name="door_no" placeholder="Door" value="{{ old('door_no') }}" 
                                    class="w-1/2 px-3.5 py-2 text-gray-800 placeholder-gray-400 focus:outline-none bg-transparent">
                            </div>
                        </div>

                        <!-- Street / Area -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Street/Area</label>
                            <input type="text" name="street_name" id="area_name" value="{{ old('street_name') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- City -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- District -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">District</label>
                            <input type="text" name="district" id="district" value="{{ old('district') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- State -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">State</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <!-- Country -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', 'India') }}" 
                                class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Section: Access Control & Plan -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-4">Access Control & Plan</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Package & Status (Dropdown Grouping) -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Package & Status Configuration</label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <select name="package_id" class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                                    @endforeach
                                </select>
                                <select name="status" class="w-full sm:w-48 px-3.5 py-2 border border-gray-200 rounded-xl text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sidebar Permissions Checkboxes -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-semibold text-gray-600">Sidebar Module Permissions</label>
                            <div class="flex flex-wrap gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50/50">
                                @php
                                    $modules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];
                                @endphp
                                @foreach($modules as $module)
                                <label for="perm_{{ $loop->index }}" class="inline-flex items-center gap-2 bg-white px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 cursor-pointer select-none hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ Str::slug($module) }}" id="perm_{{ $loop->index }}" checked
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500/20 focus:ring-2">
                                    <span>{{ $module }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Form Submissions Footer Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-xs hover:bg-emerald-700 transition-colors cursor-pointer">
                        Create Host Account
                    </button>
                    <a href="{{ route('admin.host.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 font-semibold text-sm rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Keep exact scripting tags intact -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#pincode').on('keyup', function() {
        let pincode = $(this).val();
        if (pincode.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pincode}`, function(data) {
                if (data[0].Status === "Success") {
                    let post = data[0].PostOffice[0];
                    $('#city').val(post.Block);
                    $('#district').val(post.District);
                    $('#state').val(post.State);
                    $('#area_name').val(post.Name);
                }
            });
        }
    });
</script>
@endsection