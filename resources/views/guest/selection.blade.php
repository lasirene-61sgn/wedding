@extends('layouts.guest_ui')

@section('title', 'Select Your Invitation')

@push('styles')
<style>
    .selection-container {
        width: 100%;
        max-width: 800px;
        margin: 40px auto;
        text-align: center;
        z-index: 1;
    }

    h2.title {
        font-family: 'Great Vibes', cursive;
        font-size: 3.5rem;
        color: var(--pink-dark);
        margin: 0 0 10px;
        text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);
        animation: fadeInDown 0.8s ease-out;
    }

    .subtitle {
        color: var(--dark);
        font-weight: 500;
        margin-bottom: 35px;
        font-size: 1.1rem;
        animation: fadeIn 1s ease-out 0.4s backwards;
    }

    /* Tabs Styling */
    .tabs-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 30px;
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .tab-btn {
        background: rgba(255,255,255,0.6);
        border: 2px solid transparent;
        padding: 10px 20px;
        border-radius: 50px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .tab-btn:hover {
        background: rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }

    .tab-btn.active {
        background: var(--pink-primary);
        color: white;
        box-shadow: 0 6px 15px rgba(214, 51, 132, 0.3);
    }

    /* Calendar Container */
    #calendar-wrapper {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        display: none;
        animation: fadeIn 0.5s ease-out;
        text-align: left;
    }

    /* Cards */
    .invitations-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .selection-card {
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        text-align: left;
        animation: fadeInUp 0.8s ease-out backwards;
    }

    .selection-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(214, 51, 132, 0.15);
        border-color: rgba(214, 51, 132, 0.3);
    }

    .couple-names {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.6rem;
        color: var(--pink-dark);
        margin: 8px 0 15px;
    }

    /* Status Badges */
    .status-badge-container {
        margin-bottom: 20px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-accepted { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .status-declined { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    .btn-view {
        display: inline-block;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: white;
        padding: 14px 28px;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.5px;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        margin-top: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        color: white;
    }

    .alert-info {
        background: rgba(209, 236, 241, 0.9);
        color: #0c5460;
        padding: 15px;
        border-radius: 15px;
        margin-bottom: 25px;
        text-align: center;
        border: 1px solid #bee5eb;
    }

    /* Fullcalendar overrides */
    .fc-event { cursor: pointer; }
    .fc .fc-button-primary { background-color: var(--pink-primary); border-color: var(--pink-primary); }
    .fc .fc-button-primary:hover { background-color: var(--pink-dark); border-color: var(--pink-dark); }
</style>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
@endpush

@section('content')
<div class="selection-container">
    <h2 class="title">Your Invitations</h2>
    <p class="subtitle">Select a wedding to view the details</p>

    @if(session('info'))
        <div class="alert-info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
    @endif

    <div class="tabs-container">
        <button class="tab-btn active" data-target="all">All</button>
        <button class="tab-btn" data-target="accepted">Accepted</button>
        <button class="tab-btn" data-target="pending">Pending</button>
        <button class="tab-btn" data-target="rejected">Rejected</button>
        <button class="tab-btn" data-target="calendar">📅 Calendar</button>
    </div>

    <div id="calendar-wrapper">
        <div id="calendar"></div>
    </div>

    <div class="invitations-list" id="invitations-list">
        @if($invitations->count() > 0)
            @foreach($invitations as $invite)
            @php 
                $status = $invite->status ?? 'pending'; 
                $statusClass = $status === 'declined' || $status === 'rejected' ? 'rejected' : $status;
            @endphp
            <div class="glass-panel selection-card invite-card" data-status="{{ $statusClass }}">
                <div class="status-badge-container">
                    @if($status == 'pending')
                        <span class="status-badge status-pending"><i class="fas fa-hourglass-half"></i> Response Pending</span>
                    @elseif($status == 'accepted')
                        <span class="status-badge status-accepted"><i class="fas fa-check"></i> Attending</span>
                    @else
                        <span class="status-badge status-declined"><i class="fas fa-times"></i> Declined</span>
                    @endif
                </div>

                @php 
                    $mainInvitation = \App\Models\Invitation::where('host_id', $invite->host_id)->first();
                @endphp

                <div style="color: var(--gold-dark); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1.5px; font-weight: 700;">Host</div>
                <div class="couple-names" style="font-size: 1.2rem; font-family: 'Poppins', sans-serif;">{{ $invite->host->name ?? 'Unknown Host' }}</div>

                <p style="font-size: 0.95rem; color: var(--gray); margin-bottom: 20px; line-height: 1.5;">
                    Celebrating the wedding of<br>
                    <strong style="color: var(--dark); font-size: 1.1rem;">{{ $mainInvitation->bride_name ?? 'Bride' }}</strong> & <strong style="color: var(--dark); font-size: 1.1rem;">{{ $mainInvitation->groom_name ?? 'Groom' }}</strong>
                </p>

                <a href="{{ route('guest.wedding.details', $invite->id) }}" class="btn-view">
                    Open Invitation Dashboard
                </a>
            </div>
            @endforeach
        @else
            <div class="glass-panel selection-card" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;">💌</div>
                <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 20px;">No invitations found for your number.</p>
                <a href="{{ route('guest.login') }}" style="color: var(--pink-primary); text-decoration: none; font-weight: 600; border-bottom: 2px solid var(--pink-primary); padding-bottom: 2px;">Try a different number</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const cards = document.querySelectorAll('.invite-card');
        const listWrapper = document.getElementById('invitations-list');
        const calWrapper = document.getElementById('calendar-wrapper');
        
        let calendar = null;
        let eventsData = @json($calendarEvents ?? []);

        function initCalendar() {
            if (calendar) return; // already initialized
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                events: eventsData,
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault(); // don't let the browser navigate
                    }
                }
            });
            calendar.render();
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Update active class
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const target = tab.getAttribute('data-target');

                if (target === 'calendar') {
                    listWrapper.style.display = 'none';
                    calWrapper.style.display = 'block';
                    initCalendar();
                    setTimeout(() => { calendar.updateSize(); }, 100);
                } else {
                    calWrapper.style.display = 'none';
                    listWrapper.style.display = 'flex';
                    
                    cards.forEach(card => {
                        if (target === 'all') {
                            card.style.display = 'block';
                        } else {
                            if (card.getAttribute('data-status') === target) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush