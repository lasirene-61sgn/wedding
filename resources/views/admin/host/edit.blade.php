@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 mt-6 mb-12">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-xl font-semibold text-gray-800">Edit Host: {{ $host->name }}</h4>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.host.update', $host->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <h5 class="text-indigo-600 font-bold uppercase tracking-wider text-sm mb-4">Basic Information</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" 
                            value="{{ old('name', $host->name) }}">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" 
                            value="{{ old('email', $host->email) }}">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary Mobile</label>
                        <input type="text" name="mobile" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('mobile') border-red-500 @enderror" 
                            value="{{ old('mobile', $host->mobile) }}">
                        @error('mobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alternate Number</label>
                        <input type="text" name="alternate_number" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('alternate_number', $host->alternate_number) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('whatsapp_number', $host->whatsapp_number) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            New Password <span class="text-gray-400 font-normal text-xs">(Leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="my-8 border-gray-100">

                <!-- Address Section -->
                <h5 class="text-indigo-600 font-bold uppercase tracking-wider text-sm mb-4">Address & Profile Details</h5>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                        <input type="text" name="pincode" id="pincode" maxlength="6"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('pincode', $host->pincode) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complex Name</label>
                        <input type="text" name="complex_name" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('complex_name', $host->complex_name) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Floor / Door No</label>
                        <div class="flex">
                            <input type="text" name="floor" placeholder="Floor" 
                                class="w-1/2 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                value="{{ old('floor', $host->floor) }}">
                            <input type="text" name="door_no" placeholder="Door" 
                                class="w-1/2 rounded-r-md border-t border-b border-r border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                value="{{ old('door_no', $host->door_no) }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Street/Area</label>
                        <input type="text" name="street_name" id="area_name" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('street_name', $host->street_name) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('city', $host->city) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                        <input type="text" name="district" id="district" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('district', $host->district) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" id="state" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('state', $host->state) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" id="country" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            value="{{ old('country', $host->country ?? 'India') }}">
                    </div>
                </div>

                <hr class="my-8 border-gray-100">

                <!-- Access Section -->
                <h5 class="text-indigo-600 font-bold uppercase tracking-wider text-sm mb-4">Access Control & Plan</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Package & Status</label>
                        <div class="flex space-x-2">
                            <select name="package_id" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', $host->package_id) == $package->id ? 'selected' : '' }}>
                                        {{ $package->package_name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ old('status', $host->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $host->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sidebar Permissions</label>
                        <div class="flex flex-wrap gap-4 border border-gray-200 p-4 rounded-md bg-gray-50">
                            @php
                                $modules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories', 'vendors', 'timeline', 'budget',
                                'tasks', 'checklist', 'mood-board', 'logistics', 'accommodation', 'menus', 'members', 'helping-staff', 'messaging', 'chat', 
                                'call-center', 'contacts', 'notifications', 'documents', 'contracts', 'automation', 'setup', 'master', ''];
                                $currentPermissions = $host->permissions ?? [];
                            @endphp
                            @foreach($modules as $module)
                                @php $slug = Str::slug($module); @endphp
                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $slug }}" 
                                        id="perm_{{ $loop->index }}" 
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        {{ in_array($slug, $currentPermissions) ? 'checked' : '' }}>
                                    <label for="perm_{{ $loop->index }}" class="ml-2 text-sm text-gray-600">{{ $module }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center space-x-4 pt-6 border-t border-gray-100">
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Update Host Account
                    </button>
                    <a href="{{ route('admin.host.index') }}" class="px-6 py-2.5 bg-white text-gray-700 font-medium rounded-md border border-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
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