@extends('layouts.host')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Wedding Invitations</h3>
            <p class="text-secondary small mb-0">Manage your elegant digital wedding registry</p>
        </div>
        
        @if($invitations->isEmpty())
        <a href="{{ route('host.invitation.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-2"></i>Create New Invitation
        </a>
        @endif
    </div>

    @if(session('Success') || session('Message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('Success') ?? session('Message') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($invitations as $inv)
            <div class="col-xl-6 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden invitation-card theme-{{ $inv->theme ?? 'classic' }}">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <div class="h-100 position-relative" style="min-height: 350px;">
                                    <img src="{{ asset('storage/' . $inv->wedding_image) }}" 
                                         class="w-100 h-100 position-absolute" 
                                         style="object-fit: cover; top:0; left:0;" 
                                         alt="Wedding Image">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="p-4 p-lg-5 text-center">
                                    <div class="mb-4">
                                        <div class="wedding-motif text-primary opacity-25 mb-3">
                                            <i class="bi bi-heart-fill fs-1"></i>
                                        </div>
                                        
                                        @if($inv->invite == 'brideparents')
                                            <div class="parent-names mb-2" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #64748b;">
                                                Mr. {{ $inv->bride_father_name }} & Mrs. {{ $inv->bride_mother_name }}
                                            </div>
                                            <small class="text-muted d-block mb-3 italic">cordially invite you to the wedding of their daughter</small>
                                            <h2 class="card-title-wedding mb-4" style="font-family: 'Playfair Display', serif; color: #1e293b; font-weight: 700;">
                                                {{ $inv->bride_name }} <span class="text-primary">&</span> {{ $inv->groom_name }}
                                            </h2>
                                        @elseif($inv->invite == 'groomparents')
                                            <div class="parent-names mb-2" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #64748b;">
                                                Mr. {{ $inv->groom_father_name }} & Mrs. {{ $inv->groom_mother_name }}
                                            </div>
                                            <small class="text-muted d-block mb-3 italic">cordially invite you to the wedding of their son</small>
                                            <h2 class="card-title-wedding mb-4" style="font-family: 'Playfair Display', serif; color: #1e293b; font-weight: 700;">
                                                {{ $inv->groom_name }} <span class="text-primary">&</span> {{ $inv->bride_name }}
                                            </h2>
                                        @else
                                            <div class="couple-names-header mb-4">
                                                <h2 class="card-title-wedding" style="font-family: 'Playfair Display', serif; color: #1e293b; font-weight: 700;">
                                                    {{ $inv->bride_name }} <span class="text-primary">&</span> {{ $inv->groom_name }}
                                                </h2>
                                                <small class="text-muted">Together with their families</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="venue-info p-3 bg-light rounded-4 mb-4 text-start">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                <i class="bi bi-calendar-event text-primary small"></i>
                                            </div>
                                            <span class="small fw-bold">{{ date('F j, Y', strtotime($inv->wedding_date)) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                <i class="bi bi-clock text-primary small"></i>
                                            </div>
                                            <span class="small fw-bold">{{ $inv->wedding_time }}</span>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3 mt-1" style="width: 32px; height: 32px; flex-shrink: 0;">
                                                <i class="bi bi-geo-alt text-primary small"></i>
                                            </div>
                                            <div>
                                                <span class="small fw-bold d-block text-primary">{{ $inv->venue->venue_name ?? 'Venue N/A' }}</span>
                                                <span class="xxs text-muted d-block">{{ $inv->venue->venue_address ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('host.invitation.edit', $inv->id) }}" class="btn btn-dark rounded-pill fw-bold">
                                            <i class="bi bi-pencil-square me-2"></i>Edit Details
                                        </a>
                                        <a href="#" class="btn btn-outline-primary rounded-pill fw-bold small">
                                            <i class="bi bi-eye me-2"></i>Preview Link
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4 text-muted opacity-25">
                    <i class="bi bi-envelope-paper-heart" style="font-size: 5rem;"></i>
                </div>
                <h5 class="text-secondary fw-bold">No Invitations Created</h5>
                <p class="text-muted small">Start by creating your first elegant digital wedding invitation.</p>
                <a href="{{ route('host.invitation.create') }}" class="btn btn-primary rounded-pill px-5 fw-bold mt-3">
                    Get Started
                </a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .xxs { font-size: 0.7rem; }
    .invitation-card { transition: all 0.3s ease; }
    .invitation-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .italic { font-style: italic; }
</style>
@endsection
