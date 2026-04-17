@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm max-w-lg mx-auto">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Package: {{ $package->package_name }}</h4>
            <a href="{{ route('admin.package.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
        <div class="card-body">
            {{-- Note: 'admin.package.update' matches your resource route name --}}
            <form action="{{ route('admin.package.update', $package->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Package Name</label>
                    <input type="text" name="package_name" class="form-control" 
                           value="{{ old('package_name', $package->package_name) }}" required>
                    @error('package_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="package_description" class="form-control" rows="3" required>{{ old('package_description', $package->package_description) }}</textarea>
                    @error('package_description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="price" class="form-control" 
                                   value="{{ old('price', $package->price) }}" required>
                        </div>
                        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Guest Limit</label>
                        <input type="number" name="guest_limit" class="form-control" 
                               value="{{ old('guest_limit', $package->guest_limit) }}" required>
                        @error('guest_limit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Invite Limit</label>
                        <input type="number" name="invite_limit" class="form-control" 
                               value="{{ old('invite_limit', $package->invite_limit) }}" required>
                        @error('invite_limit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary w-100">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection