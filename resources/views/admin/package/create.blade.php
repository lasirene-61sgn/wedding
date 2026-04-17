@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm max-w-lg mx-auto">
        <div class="card-header bg-white"><h4>Create Package</h4></div>
        <div class="card-body">
            <form action="{{ route('admin.package.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Package Name</label>
                    <input type="text" name="package_name" class="form-control" value="{{ old('package_name') }}" placeholder="e.g. Gold Plan" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="package_description" class="form-control" rows="3">{{ old('package_description') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Guest Limit</label>
                        <input type="number" name="guest_limit" class="form-control" value="{{ old('guest_limit') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Invite Limit</label>
                        <input type="number" name="invite_limit" class="form-control" value="{{ old('invite_limit') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">Save Package</button>
            </form>
        </div>
    </div>
</div>
@endsection