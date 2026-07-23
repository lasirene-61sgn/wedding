@extends('layouts.host')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hostceramonyindex.css') }}">
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">My Ceremonies</h3>
            <p class="text-secondary small mb-0">Manage all your wedding events and schedules</p>
        </div>
        <a href="{{ route('host.ceramony.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-2"></i>Add New Ceremony
        </a>

    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        @forelse($ceramonies as $item)
        <div class="col-md-6 col-xl-4">
            <div class="card ceremony-card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="position-relative">
                    <div class="action-buttons">
                        @if($item->is_main)
                        <a href="{{ route('host.invitation.index') }}" class="btn-action btn-edit" title="Edit in Invitation">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        @else
                        <a href="{{ route('host.ceramony.edit', $item->id) }}" class="btn-action btn-edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('host.ceramony.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this ceremony?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="image-container">
                        @if($item->is_main)
                        <span class="main-wedding-badge">MAIN WEDDING</span>
                        @endif

                        @if($item->ceramony_image)
                        <img src="{{ asset('storage/' . $item->ceramony_image) }}" alt="{{ $item->ceramony_name }}">
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-light h-100 text-muted">
                            <i class="bi bi-image fs-1 opacity-25"></i>
                        </div>
                        @endif
                        <span class="category-badge shadow-sm">
                            {{ $item->category->category_name ?? $item->category->name }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">{{ $item->ceramony_name }}</h5>

                    <div class="d-flex align-items-start mb-4">
                        <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i>
                        <div class="small">
                            @if($item->venue)
                            <strong class="text-primary">{{ $item->venue->venue_name }}</strong><br>
                            <p class="text-muted mb-0">{{ $item->venue->venue_address }}</p>
                            <p class="text-muted mb-0 small">{{ $item->venue->district }}, {{ $item->venue->state }}</p>
                            @else
                            <span class="text-danger fw-bold">No Venue Assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-0 border-top pt-3">
                        <div class="col-6">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Date</small>
                            <span class="small fw-bold"><i class="bi bi-calendar-event text-primary me-1"></i> {{ $item->ceramony_date ?? 'TBD' }}</span>
                        </div>
                        <div class="col-6 border-start ps-3">
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Time</small>
                            <span class="small fw-bold"><i class="bi bi-clock text-primary me-1"></i> {{ $item->ceramony_time ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>

                @if($item->is_main)
                <div class="card-footer bg-light border-0 py-2 text-center">
                    <small class="text-muted fst-italic"><i class="bi bi-info-circle me-1"></i> Managed from Invitation</small>
                </div>
                @endif
                @if($item->canva_design_url)
                <div class="card-footer bg-light border-0 py-2 text-center {{ $item->is_main ? 'pt-0' : '' }}">
                    <a href="{{ $item->canva_design_url }}" target="_blank" class="btn btn-sm btn-info text-white w-100 rounded-pill"><i class="bi bi-magic me-1"></i> View Canva Design</a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://illustrations.popsy.co/gray/calendar.svg" alt="No data" style="width: 200px;" class="mb-4">
            <h5 class="text-muted">No ceremonies found.</h5>
            <p class="text-muted small">Create your first ceremony to start organizing your wedding events.</p>
            <a href="{{ route('host.ceramony.create') }}" class="btn btn-primary rounded-pill px-4">Create Now</a>
        </div>
        @endforelse
    </div>
</div>


@endsection