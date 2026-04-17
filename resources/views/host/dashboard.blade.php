@extends('layouts.host')

@section('title', 'Host Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-4 shadow-sm border border-light">
                <div>
                    <h2 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">Welcome back, {{ Auth::guard('host')->user()->name }}!</h2>
                    <p class="text-secondary mb-0">Here's what's happening with your wedding planning today.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <div class="small text-muted mb-1">Today's Date</div>
                    <div class="fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>{{ date('F j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stats-card overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="stats-icon bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <h6 class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Guests</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total_guests'] }}</h3>
                    <div class="stats-bg-icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stats-card overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="stats-icon bg-success-subtle text-success mb-3">
                        <i class="bi bi-calendar-heart fs-4"></i>
                    </div>
                    <h6 class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Ceremonies</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['ceremonies_count'] }}</h3>
                    <div class="stats-bg-icon"><i class="bi bi-calendar-heart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stats-card overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="stats-icon bg-info-subtle text-info mb-3">
                        <i class="bi bi-envelope-check fs-4"></i>
                    </div>
                    <h6 class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Invites Sent</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['invitations_sent'] }}</h3>
                    <div class="stats-bg-icon"><i class="bi bi-envelope-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stats-card overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="stats-icon bg-warning-subtle text-warning mb-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <h6 class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Pending RSVP</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                    <div class="stats-bg-icon"><i class="bi bi-clock-history"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Calendar Section -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;"><i class="bi bi-calendar3 me-2 text-primary"></i>Wedding Schedule</h5>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-pill" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('host.ceramony.index') }}">View All Events</a></li>
                                <li><a class="dropdown-item" href="{{ route('host.ceramony.create') }}">Add New Event</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- RSVP Status Section -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;"><i class="bi bi-pie-chart me-2 text-primary"></i>RSVP Insights</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Accepted</span>
                            <span class="small fw-bold text-success">{{ $stats['accepted'] }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-success rounded-pill" style="width: {{ $stats['total_guests'] > 0 ? ($stats['accepted'] / $stats['total_guests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Pending</span>
                            <span class="small fw-bold text-warning">{{ $stats['pending'] }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-warning rounded-pill" style="width: {{ $stats['total_guests'] > 0 ? ($stats['pending'] / $stats['total_guests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Rejected</span>
                            <span class="small fw-bold text-danger">{{ $stats['rejected'] }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-danger rounded-pill" style="width: {{ $stats['total_guests'] > 0 ? ($stats['rejected'] / $stats['total_guests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-light rounded-4 border border-dashed">
                        <div class="mb-3 text-primary">
                            <i class="bi bi-chat-heart fs-2"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Planning Assistance</h6>
                        <p class="text-muted small mb-3">Keep your guests engaged. Send follow-up reminders to pending RSVPs.</p>
                        <a href="{{ route('host.guestlist.index') }}?status=pending" class="btn btn-primary btn-sm rounded-pill px-4">Manage Pending</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<style>
    .stats-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1) !important;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
    .stats-bg-icon {
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 80px;
        opacity: 0.05;
        transform: rotate(-15deg);
    }
    .rounded-4 { border-radius: 1rem !important; }
    
    /* Calendar Customization */
    .fc { font-family: 'Outfit', sans-serif; border: none !important; }
    .fc-header-toolbar { margin-bottom: 2rem !important; }
    .fc-button-primary { 
        background-color: var(--accent-color) !important; 
        border: none !important;
        border-radius: 10px !important;
        padding: 8px 16px !important;
        text-transform: capitalize !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    .fc-button-primary:hover {
        background-color: #4338ca !important;
    }
    .fc-event {
        border: none !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
        cursor: pointer;
        background-color: var(--accent-color) !important;
    }
    .fc-day-today { background-color: rgba(79, 70, 229, 0.03) !important; }
    .fc-daygrid-day-number { font-weight: 600; color: var(--secondary-color); text-decoration: none; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: [
                @foreach($ceremonies as $ceremony)
                {
                    title: '{{ $ceremony->ceramony_name }}',
                    start: '{{ $ceremony->ceramony_date }}',
                    allDay: true,
                    extendedProps: {
                        time: '{{ $ceremony->ceramony_time }}'
                    }
                },
                @endforeach
            ],
            eventClick: function(info) {
                alert('Ceremony: ' + info.event.title + '\nTime: ' + info.event.extendedProps.time);
            },
            height: 'auto',
            themeSystem: 'bootstrap5'
        });
        calendar.render();
    });
</script>
@endpush
@endsection