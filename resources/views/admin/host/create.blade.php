@extends('layouts.admin')

@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Create New Host</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.host.store') }}" method="POST">
                @csrf

                <h5 class="text-primary mb-3">Basic Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Primary Mobile</label>
                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Alternate Number</label>
                        <input type="text" name="alternate_number" class="form-control" value="{{ old('alternate_number') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Account Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    </div>
                </div>

                <hr>

                <h5 class="text-primary mb-3">Address & Profile Details</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" id="pincode" class="form-control" value="{{ old('pincode') }}" maxlength="6">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Complex Name</label>
                        <input type="text" name="complex_name" class="form-control" value="{{ old('complex_name') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Floor / Door No</label>
                        <div class="input-group">
                            <input type="text" name="floor" class="form-control" placeholder="Floor" value="{{ old('floor') }}">
                            <input type="text" name="door_no" class="form-control" placeholder="Door" value="{{ old('door_no') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Street/Area</label>
                        <input type="text" name="street_name" id="area_name" class="form-control" value="{{ old('street_name') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">District</label>
                        <input type="text" name="district" id="district" class="form-control" value="{{ old('district') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', 'India') }}">
                    </div>
                </div>

                <hr>

                <h5 class="text-primary mb-3">Access Control & Plan</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Package & Status</label>
                        <div class="input-group mb-2">
                            <select name="package_id" class="form-select">
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sidebar Permissions</label>
                        <div class="d-flex flex-wrap gap-3 border p-2 rounded">
                            @php
                                $modules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];
                            @endphp
                            @foreach($modules as $module)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ Str::slug($module) }}" id="perm_{{ $loop->index }}" checked>
                                    <label class="form-check-label" for="perm_{{ $loop->index }}">{{ $module }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success px-5 shadow-sm">Create Host Account</button>
                    <a href="{{ route('admin.host.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

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