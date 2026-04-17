@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Service package</h2>
        <a href="{{ route('admin.package.create') }}" class="btn btn-primary">Create New Package</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Package Name</th>
                        <th>Price</th>
                        <th>Guest Limit</th>
                        <th>Invite Limit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td><strong>{{ $package->package_name }}</strong></td>
                        <td>${{ number_format($package->price, 2) }}</td>
                        <td>{{ $package->guest_limit }}</td>
                        <td>{{ $package->invite_limit }}</td>
                        <td>
                            <a href="{{ route('admin.package.edit', $package->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this package?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection