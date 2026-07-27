@extends('layouts.host')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Save The Dates</h3>
            <p class="text-secondary small mb-0">Manage your digital wedding announcements and countdowns</p>
        </div>
    </div>

    @if(session('Success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('Success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($savedates as $sd)
            <div class="col-xl-5 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden save-the-date-card">
                    <div class="card-body p-0">
                        
                        {{-- 1. Image & Names Overlay Section --}}
                        <div class="position-relative">
                            @if(!empty($sd->image))
                                <img src="{{ asset('storage/' . $sd->image) }}" class="w-100" alt="Save the Date" style="height: 450px; object-fit: cover;">
                            @else
                                <div class="w-100 bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <span class="text-muted opacity-75 fs-5">No Image Available</span>
                                </div>
                            @endif

                            @if(!empty($sd->invitation->bride_name) || !empty($sd->invitation->groom_name))
                                <div class="overlay-content p-4 d-flex flex-column justify-content-end text-white text-center">
                                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 2.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                        {{ $sd->invitation->bride_name ?? '' }}
                                        @if(!empty($sd->invitation->bride_name) && !empty($sd->invitation->groom_name))
                                            <span class="text-accent">&</span>
                                        @endif
                                        {{ $sd->invitation->groom_name ?? '' }}
                                    </h2>
                                    <p class="mb-4 text-uppercase fw-bold tracking-widest" style="font-size: 0.8rem; letter-spacing: 4px;">Are Getting Married</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4 p-lg-5">
                            <div class="text-center mb-5">
                                
                                {{-- 2. Message Quote Section --}}
                                @if(!empty($sd->message))
                                    <div class="message-quote mb-4">
                                        <i class="bi bi-quote fs-1 text-primary opacity-25"></i>
                                        <p class="fst-italic text-secondary fs-5">"{{ $sd->message }}"</p>
                                    </div>
                                @endif
                                
                                {{-- 3. Countdown Section --}}
                                @if(!empty($sd->invitation->wedding_date))
                                    <div class="bg-light rounded-4 p-4 border border-dashed shadow-xs mb-4">
                                        <div class="small text-uppercase fw-bold text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 2px;">Wedding Countdown</div>
                                        <h2 class="countdown-timer fw-bold mb-0 text-primary" 
                                                data-date="{{ $sd->invitation->wedding_date }}" 
                                                data-time="{{ $sd->invitation->wedding_time ?? '00:00:00' }}"
                                                style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">
                                            --:--:--:--
                                        </h2>
                                    </div>
                                @endif
                                
                                {{-- 4. Date & Venue Section --}}
                                @if(!empty($sd->invitation->wedding_date) || !empty($sd->invitation->venue->venue_name))
                                    <div class="wedding-details mb-4">
                                        @if(!empty($sd->invitation->wedding_date))
                                            <div class="fw-bold fs-5 mb-1">{{ date('F j, Y', strtotime($sd->invitation->wedding_date)) }}</div>
                                        @endif

                                        @if(!empty($sd->invitation->venue->venue_name))
                                            <div class="text-muted">{{ $sd->invitation->venue->venue_name }}</div>
                                        @endif
                                    </div>
                                @endif

                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-3">
                                <a href="{{ route('host.savedate.edit', $sd->id) }}" class="btn btn-dark flex-grow-1 rounded-pill fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i>Edit Details
                                </a>
                                <form action="{{ route('host.savedate.destroy', $sd->id) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger rounded-pill fw-bold" onclick="return confirm('Delete this announcement?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4 text-muted opacity-25">
                    <i class="bi bi-calendar-heart" style="font-size: 5rem;"></i>
                </div>
                <h5 class="text-secondary fw-bold">No Announcements Yet</h5>
                <p class="text-muted small">Share the big news with your guests via a beautiful digital announcement. Create an invitation to automatically generate your Save the Date.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .save-the-date-card { transition: all 0.3s ease; }
    .save-the-date-card:hover { transform: translateY(-5px); box-shadow: 0 1.5rem 4rem rgba(0,0,0,0.12) !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .overlay-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.8) 100%);
    }
    .text-accent { color: #fecdd3; }
</style>

@if(count($savedates) > 0)
<script>
    function updateCountdowns() {
        const timers = document.querySelectorAll('.countdown-timer');
        if (timers.length === 0) return;

        timers.forEach(function(timer) {
            const date = timer.getAttribute('data-date');
            const time = timer.getAttribute('data-time');
            
            if (!date) return;

            const target = new Date(date + "T" + (time || "00:00:00")).getTime();
            const now = new Date().getTime();
            const diff = target - now;

            if (diff <= 0) {
                timer.innerHTML = "Wedding Day is Here!";
                return;
            }

            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            timer.innerHTML = `${d}d ${h}h ${m}m ${s}s`;
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>
@endif
@endsection