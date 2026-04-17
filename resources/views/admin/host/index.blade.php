@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Hosts</h2>
        <a href="{{ route('admin.host.create') }}" class="btn btn-primary">Add New Host</a>
    </div>

    <div class="card shadow-sm">
        <table class="table table-hover align-middle bg-white mb-0">
            <thead class="table-light">
                <tr>
                    <th>Host Info</th>
                    <th>Package</th>
                    <th>Created By</th> {{-- New Column --}}
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hosts as $host)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $host->name }}</div>
                        <small class="text-muted">{{ $host->email }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $host->package->package_name ?? 'No Package' }}
                        </span>
                    </td>
                    <td>
                        {{-- Show the Admin's name or 'System' if null --}}
                        <span class="text-secondary">
                            {{ $host->creator->name ?? 'System' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $host->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($host->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.host.edit', $host->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.host.destroy', $host->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection