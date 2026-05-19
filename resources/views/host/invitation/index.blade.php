@extends('layouts.host')

@section('content')
<!-- Google Fonts for Premium Typography -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Dancing+Script:wght@400;600&family=Playfair+Display:wght@400;600;700&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/hostinvitationindex.css') }}">
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">✨ Wedding Invitations</h3>
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
                            <!-- Image Section -->
                            <div class="col-md-5">
                                <div class="card-image-wrapper position-relative h-100">
                                    <img src="{{ asset('storage/' . $inv->wedding_image) }}" 
                                         class="w-100 h-100 position-absolute" 
                                         style="object-fit: cover; top:0; left:0;" 
                                         alt="Wedding Image">
                                    <div class="card-image-overlay"></div>
                                    
                                    <!-- Theme Badge -->
                                    <span class="theme-badge">
                                        {{ $inv->theme == 'classic' ? 'Classic' : ($inv->theme == 'royal' ? 'Royal' : 'Floral') }}
                                    </span>
                                    
                                    <!-- Date Overlay -->
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white z-2" style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-calendar-heart small"></i>
                                            <span class="small fw-bold">{{ date('M j, Y', strtotime($inv->wedding_date)) }}</span>
                                            <span class="xxs opacity-75">•</span>
                                            <span class="xxs">{{ $inv->wedding_time }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="col-md-7">
                                <div class="p-4 p-lg-4 text-center position-relative" style="z-index: 2;">
                                    
                                    <!-- Decorative Divider -->
                                    <div class="decorative-divider"></div>
                                    
                                    <!-- Inviter & Names Section -->
                                    <div class="mb-3">
                                        @if($inv->invite == 'brideparents')
                                            <div class="parent-names mb-2" style="font-family: 'Outfit', sans-serif;">
                                                Mr. {{ $inv->bride_father_name }} & Mrs. {{ $inv->bride_mother_name }}
                                            </div>
                                            <small class="text-muted d-block mb-2 italic">cordially invite you to the wedding of their daughter</small>
                                            <div class="couple-names">
                                                <span class="card-title-wedding">{{ $inv->bride_name }}</span> 
                                                <span class="text-primary mx-1" style="font-family: 'Dancing Script', cursive;">&</span> 
                                                <span class="card-title-wedding">{{ $inv->groom_name }}</span>
                                            </div>
                                        @elseif($inv->invite == 'groomparents')
                                            <div class="parent-names mb-2" style="font-family: 'Outfit', sans-serif;">
                                                Mr. {{ $inv->groom_father_name }} & Mrs. {{ $inv->groom_mother_name }}
                                            </div>
                                            <small class="text-muted d-block mb-2 italic">cordially invite you to the wedding of their son</small>
                                            <div class="couple-names">
                                                <span class="card-title-wedding">{{ $inv->groom_name }}</span> 
                                                <span class="text-primary mx-1" style="font-family: 'Dancing Script', cursive;">&</span> 
                                                <span class="card-title-wedding">{{ $inv->bride_name }}</span>
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                <div class="couple-names">
                                                    <span class="card-title-wedding">{{ $inv->bride_name }}</span> 
                                                    <span class="text-primary mx-1" style="font-family: 'Dancing Script', cursive;">&</span> 
                                                    <span class="card-title-wedding">{{ $inv->groom_name }}</span>
                                                </div>
                                                <small class="text-muted">Together with their families</small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Decorative Divider -->
                                    <div class="decorative-divider"></div>

                                    <!-- Venue Information - FULL DETAILS -->
                                    <div class="venue-info text-start">
                                        <span class="venue-name">{{ $inv->venue->venue_name ?? 'Venue Details' }}</span>
                                        
                                        <div class="venue-line">
                                            <div class="venue-icon"><i class="bi bi-geo-alt"></i></div>
                                            <span class="venue-address">{{ $inv->venue->venue_address ?? 'Address not provided' }}</span>
                                        </div>
                                        
                                        @if($inv->venue->wedding_location)
                                        <div class="venue-line">
                                            <div class="venue-icon"><i class="bi bi-signpost"></i></div>
                                            <span class="venue-location">Landmark: {{ $inv->venue->wedding_location }}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="venue-line">
                                            <div class="venue-icon"><i class="bi bi-building"></i></div>
                                            <span class="venue-location">
                                                {{ $inv->venue->area_name ?? '' }}
                                                @if($inv->venue->area_name && $inv->venue->district), @endif
                                                {{ $inv->venue->district ?? '' }}
                                                @if($inv->venue->district && $inv->venue->state), @endif
                                                {{ $inv->venue->state ?? '' }}
                                                @if($inv->venue->pincode) - {{ $inv->venue->pincode }}@endif
                                            </span>
                                        </div>
                                        
                                        @if($inv->venue->location_map)
                                        <div class="venue-line mt-2">
                                            <div class="venue-icon"><i class="bi bi-map"></i></div>
                                            <a href="{{ $inv->venue->location_map }}" target="_blank" class="text-decoration-none small fw-bold" style="color: inherit;">
                                                🗺️ Open in Maps
                                            </a>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2 mt-4">
                                        <a href="{{ route('host.invitation.edit', $inv->id) }}" class="btn btn-dark rounded-pill btn-edit">
                                            <i class="bi bi-pencil-square me-2"></i>Edit Invitation
                                        </a>
                                        <a href="#" class="btn btn-outline-primary rounded-pill btn-preview small">
                                            <i class="bi bi-eye me-2"></i>View Public Link
                                        </a>
                                    </div>
                                    
                                    <!-- Footer Note -->
                                    <small class="text-muted d-block mt-3" style="font-size: 0.7rem; opacity: 0.7;">
                                        Created: {{ $inv->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-envelope-paper-heart text-primary"></i>
                    <h5 class="text-secondary fw-bold mb-2">No Invitations Created Yet</h5>
                    <p class="text-muted small mb-4">Start by creating your first elegant digital wedding invitation.</p>
                    <a href="{{ route('host.invitation.create') }}" class="btn btn-primary rounded-pill px-5 fw-bold">
                        <i class="bi bi-plus-lg me-2"></i>Create Your First Invitation
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    // Optional: Add smooth hover effects enhancement
    document.querySelectorAll('.invitation-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '5';
        });
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
</script>
@endsection