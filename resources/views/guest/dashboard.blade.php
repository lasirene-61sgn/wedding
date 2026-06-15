<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Invitation | {{ $invite->host->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #d63384;
            --pink-dark: #b02663;
            --pink-light: #fff5f8;
            --gold: #bfa15f;
            --gold-dark: #8c6f31;
            --dark: #1a1a1a;
            --gray: #5a5a5a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fcf8f4;
            margin: 0;
            padding: 0;
            color: var(--dark);
            min-height: 100vh;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header Layout */
        header {
            background: #ffffff;
            padding: 50px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(214, 51, 132, 0.06);
        }

        header .decorative-ornament {
            font-size: 1.5rem;
            color: var(--gold);
            margin-bottom: 10px;
            letter-spacing: 4px;
        }

        header h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 4rem;
            color: var(--pink-primary);
            margin: 0;
            animation: elegantFadeIn 1.2s ease-out;
        }

        header p {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--dark);
            margin-top: 10px;
            font-style: italic;
        }

        /* FLOATING PILL NAV BAR */
        .nav-bar-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 20px;
            background: rgba(253, 248, 244, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .nav-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 8px 30px rgba(140, 111, 49, 0.08);
            border: 1px solid rgba(191, 161, 95, 0.2);
        }

        .nav-bar a {
            text-decoration: none;
            color: var(--gray);
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-bar a:hover {
            color: var(--pink-primary);
            background: var(--pink-light);
        }

        .nav-bar a.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.25);
        }

        /* Main Structural Dashboard Split */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 40px;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px 120px;
            box-sizing: border-box;
        }

        .welcome-pane {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .welcome-badge-card {
            background: #ffffff;
            border: 1px solid rgba(191, 161, 95, 0.15);
            padding: 40px 30px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }

        .invitation-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 20px;
            margin-top: 25px;
            border: 1px solid #f0e6eb;
        }

        .details-box {
            background: var(--pink-light);
            padding: 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            text-align: left;
            border-left: 3px solid var(--pink-primary);
        }

        .status-wrapper {
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-accepted { background: #d4edda; color: #155724; }
        .status-declined { background: #f8d7da; color: #721c24; }

        .schedule-pane {
            display: flex;
            flex-direction: column;
        }

        .pane-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin: 0 0 25px 0;
        }

        /* -------------------------------------------------------------
           NEW GLOBAL FIXED INVITATION STYLE (PC, DESKTOP, TAB, MOBILE SAME)
        ------------------------------------------------------------- */
        .ceremony-card {
            background-color: #fff9e6; 
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            position: relative;
            box-sizing: border-box;
            
            /* CRITICAL FIX: Forces card to keep standard portrait proportions like image_bb3d66.jpg */
            width: 100%;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            aspect-ratio: 3 / 4; 
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 25px;
        }

        /* background image mapping configuration */
        .ceremony-card.has-bg {
            background-image: var(--bg-url) !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            background-size: contain !important; /* Contains full image boundaries inside the container safely */
            background-color: #fff9e6;
        }

        /* Completely floating, flat background configuration on all devices (No Hovers) */
        .ceremony-card .card-content {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100%;
            text-align: center;
            z-index: 2;
        }

        /* Typography & Modern delayed sequential animations across all viewports */
        .ceremony-card .ceremony-title {
            font-family: 'Georgia', cursive, serif !important;
            font-size: 2.5rem !important;
            font-weight: 600;
            margin: 0 0 20px 0 !important;
            color: #b02663 !important; 
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 1), -2px -2px 4px rgba(255, 255, 255, 1);
            
            animation: modernPopIn 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .ceremony-card .details-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.2rem !important;
            margin-bottom: 15px !important;
            color: #2b4c5e !important; 
            font-weight: 600;
            text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
            
            opacity: 0;
            animation: modernSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .ceremony-card .details-row.date-row { animation-delay: 0.3s; }
        .ceremony-card .details-row.time-row { animation-delay: 0.5s; }
        .ceremony-card .details-row.venue-row { animation-delay: 0.7s; }
        
        .ceremony-card .venue-address {
            font-size: 1.05rem !important;
            color: #4a5568 !important;
            font-weight: 500;
            margin-top: 5px !important;
            padding: 0 20px !important;
            text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
            
            opacity: 0;
            animation: modernSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 0.9s;
        }

        /* Bottom Floating RSVP Bar Interaction */
        .floating-rsvp {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            z-index: 1000;
            box-sizing: border-box;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .floating-rsvp form {
            flex: 1;
            max-width: 250px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            background: var(--pink-primary);
            color: white;
            transition: 0.2s ease;
            font-size: 0.95rem;
        }

        .btn-decline { background: #6c757d; }
        .btn-reset { background: transparent; color: var(--pink-primary); border: 2px solid var(--pink-primary); }

        /* Media Viewport Adaptations */
        @media (max-width: 991px) {
            .main-layout {
                grid-template-columns: 1fr;
                margin: 20px auto;
            }
            .welcome-pane { position: static; }
        }

        @media (max-width: 767px) {
            header h1 { font-size: 2.8rem; }
            .nav-bar-container { padding: 10px 10px; }
            .nav-bar { width: 100%; gap: 5px; }
            .nav-bar a { font-size: 0.75rem; padding: 8px 12px; gap: 4px; }
            .ceremony-card { aspect-ratio: 3 / 4 !important; max-width: 100%; }
            .ceremony-card .ceremony-title { font-size: 2rem !important; }
        }

        /* MODERN TEXT ANIMATION DEFINITIONS */
        @keyframes modernPopIn {
            from { opacity: 0; transform: scale(0.8) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes modernSlideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <div class="page-wrapper">
        <header>
            <div class="decorative-ornament">❖ • ⚜ • ❖</div>
            <h1>{{ $invite->host->name }}</h1>
            <p>Celebrating the Union of <br><strong>{{ $invite->host->bride_name }} & {{ $invite->host->groom_name }}</strong></p>
        </header>

        <div class="nav-bar-container">
            <div class="nav-bar">
                <a href="{{ route('guest.select') }}">↩ Links</a>
                <a href="#" class="active">🏠 Home</a>
                <a href="{{ route('guest.profile.edit', $invite->id) }}">👤 Profile</a>
                <a href="{{ route('guest.gallery', $invite->id) }}">📸 Gallery</a>
            </div>
        </div>

        <div class="main-layout">
            
            <!-- Left Panel Summary Card -->
            <div class="welcome-pane">
                <div class="welcome-badge-card">
                    <h2>Hello, {{ $invite->guest_name }}!</h2>
                    <p style="color: var(--gray); margin-bottom: 25px; font-style: italic;">You are cordially invited to celebrate with us</p>
                    
                    @if(session('success'))
                        <div class="alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 10px; margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="invitation-card">
                        <h3>Wedding RSVP Status</h3>
                        <div class="details-box">
                            <strong>Your Assigned Ceremonies:</strong><br>
                            {{ $invite->assigned_ceremonies ?? 'Wedding Main Ceremony' }}
                        </div>

                        <div class="status-wrapper">
                            <span class="status-label">Your RSVP Choice:</span>
                            @php $status = $invite->status ?? 'pending'; @endphp

                            @if($status == 'pending')
                                <span class="status-badge status-pending">Pending Response</span>
                            @elseif($status == 'accepted')
                                <span class="status-badge status-accepted">Attending ✓</span>
                            @else
                                <span class="status-badge status-declined">Declined ✗</span>
                            @endif
                        </div>

                        @if($status != 'pending')
                            <form action="{{ route('guest.update_status', $invite->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-reset">Change RSVP Choice</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Timeline Event Stream -->
            <div class="schedule-pane">
                <h3 class="pane-title">Ceremony Schedule</h3>

                @forelse($detailedCeremonies as $ceremony)
                    <div class="ceremony-card {{ $ceremony->background ? 'has-bg' : '' }}"
                         style="
                            @if($ceremony->background) 
                                --bg-url: url('{{ asset('storage/' . $ceremony->background->image_path) }}');
                            @endif
                         ">

                        <!-- Inner Text Content Box (No Hover masks - Always fully exposed with safe drop shadows) -->
                        <div class="card-content">
                            <h4 class="ceremony-title">
                                {{ $ceremony->ceramony_name }}
                            </h4>

                            <div class="details-row date-row">
                                <span>📅</span> {{ \Carbon\Carbon::parse($ceremony->ceramony_date)->format('l, d F Y') }}
                            </div>

                            <div class="details-row time-row">
                                <span>⏰</span> {{ \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') }}
                            </div>

                            <div class="details-row venue-row">
                                <span>📍</span>
                                @if($ceremony->venue)
                                    <strong>{{ $ceremony->venue->venue_name }}</strong>
                                @else
                                    <strong>Venue to be announced</strong>
                                @endif
                            </div>

                            @if($ceremony->venue && $ceremony->venue->address)
                                <div class="venue-address">
                                    {{ $ceremony->venue->address }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="text-align:center; color: var(--gray); padding: 40px; background: #fff; border-radius: 20px;">No ceremonies scheduled yet.</p>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Sticky Affirmation Footer Controls -->
    @if($status == 'pending')
    <div class="floating-rsvp">
        <form action="{{ route('guest.update_status', $invite->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="accepted">
            <button type="submit" class="btn">I am coming</button>
        </form>

        <form action="{{ route('guest.update_status', $invite->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="declined">
            <button type="submit" class="btn btn-decline">Not coming</button>
        </form>
    </div>
    @endif

</body>
</html>