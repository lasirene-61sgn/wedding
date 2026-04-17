@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">All Host Invitations</h2>
        <a href="{{ route('admin.invitation.create') }}" class="btn btn-primary px-4">Create New Invitation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Host Name</th>
                        <th>Wedding Couple</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invitations as $inv)
                    <tr>
                        <td class="align-middle">
                            <span class="fw-bold text-primary">{{ $inv->host->name ?? 'Unknown Host' }}</span>
                        </td>
                        <td class="align-middle">{{ $inv->bride_name }} & {{ $inv->groom_name }}</td>
                        <td class="align-middle">
                            {{ $inv->wedding_date }}<br>
                            <small class="text-muted">{{ $inv->wedding_time }}</small>
                        </td>
                        <td class="align-middle">{{ $inv->venue->venue_name ?? 'N/A' }}</td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <a href="{{ route('admin.invitation.edit', $inv->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="{{ route('admin.invitation.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Delete this invitation?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $invitations->links() }}
    </div>
</div>
@endsection