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
            padding: 40px 20px;
            
            /* Initial state for scroll animation */
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Triggered when element enters viewport */
        .ceremony-card.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        /* background image mapping configuration */
        .ceremony-card.has-bg {
            background-image: var(--bg-url) !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            background-size: cover !important; /* Cover to fill the whole card */
            background-color: #fff9e6;
        }

        /* Completely floating, flat background configuration on all devices (No Hovers) */
        .ceremony-card .card-content {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: auto !important;
            width: 100%;
            height: 100%;
            text-align: center;
            z-index: 2;
        }

        /* Typography & Modern delayed sequential animations across all viewports */
        .ceremony-card .ceremony-title {
            font-size: 2.2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            margin: 0 !important;
            line-height: 1.2;
            width: max-content;
            min-width: 50px;
            padding: 5px;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 1), -2px -2px 4px rgba(255, 255, 255, 1);
            
            position: absolute;
            transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .ceremony-card.in-view .ceremony-title {
            transition-delay: 0.2s;
        }

        .ceremony-card .details-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 0 !important;
            line-height: 1.2;
            width: max-content;
            min-width: 50px;
            padding: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
            
            position: absolute;
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .ceremony-card.in-view .details-row.date-row { transition-delay: 0.4s; }
        .ceremony-card.in-view .details-row.time-row { transition-delay: 0.6s; }
        .ceremony-card.in-view .details-row.venue-row { transition-delay: 0.8s; }
        .ceremony-card.in-view .details-row.text-row { transition-delay: 0.6s; }
        
        .ceremony-card .venue-address {
            font-size: 1rem !important;
            color: #4a5568 !important;
            font-weight: 500;
            margin-top: 5px !important;
            padding: 0 10px !important;
            text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
            
            opacity: 0;
            transform: translateY(25px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .ceremony-card.in-view .venue-address {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 1.0s;
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

        /* Dynamic Animations Setup */
        .anim-element {
            opacity: 0; /* Hidden initially until in-view */
        }
        
        /* When in view, apply the animation. Note: animation-fill-mode is forwards so they stay visible */
        .ceremony-card.in-view .anim-element.anim-none {
            opacity: 1; /* Fallback for no animation */
        }
        
        @keyframes fadeInAnim {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .ceremony-card.in-view .anim-fade-in {
            animation-name: fadeInAnim;
            animation-fill-mode: forwards;
        }

        @keyframes slideUpAnim {
            from { opacity: 0; transform: translateY(30px) var(--tx, translateX(-50%)); }
            to { opacity: 1; transform: translateY(0) var(--tx, translateX(-50%)); }
        }
        .ceremony-card.in-view .anim-slide-up {
            animation-name: slideUpAnim;
            animation-fill-mode: forwards;
        }

        @keyframes slideDownAnim {
            from { opacity: 0; transform: translateY(-30px) var(--tx, translateX(-50%)); }
            to { opacity: 1; transform: translateY(0) var(--tx, translateX(-50%)); }
        }
        .ceremony-card.in-view .anim-slide-down {
            animation-name: slideDownAnim;
            animation-fill-mode: forwards;
        }

        @keyframes zoomInAnim {
            from { opacity: 0; transform: scale(0.8) var(--tx, translateX(-50%)); transform-origin: left center; }
            to { opacity: 1; transform: scale(1) var(--tx, translateX(-50%)); transform-origin: left center; }
        }
        .ceremony-card.in-view .anim-zoom-in {
            animation-name: zoomInAnim;
            animation-fill-mode: forwards;
        }

        @keyframes bounceAnim {
            0% { opacity: 0; transform: translateY(20px) var(--tx, translateX(-50%)); }
            50% { opacity: 1; transform: translateY(-10px) var(--tx, translateX(-50%)); }
            100% { opacity: 1; transform: translateY(0) var(--tx, translateX(-50%)); }
        }
        .ceremony-card.in-view .anim-bounce {
            animation-name: bounceAnim;
            animation-fill-mode: forwards;
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

                        <!-- Inner Text Content Box -->
                        <div class="card-content" style="position: relative; width: 100%; height: 100%;">
                            @php
                                $pos = $ceremony->text_positions ?? [];
                                $customTexts = $ceremony->custom_canvas_texts ?? [];

                                $defaults = [
                                    'preview_title' => ['top' => '10%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '2.2rem'],
                                    'preview_date_row' => ['top' => '30%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem'],
                                    'preview_time_row' => ['top' => '45%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem'],
                                    'preview_venue_row' => ['top' => '60%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem']
                                ];

                                $title_pos = array_merge($defaults['preview_title'], $pos['preview_title'] ?? []);
                                $date_pos = array_merge($defaults['preview_date_row'], $pos['preview_date_row'] ?? []);
                                $time_pos = array_merge($defaults['preview_time_row'], $pos['preview_time_row'] ?? []);
                                $venue_pos = array_merge($defaults['preview_venue_row'], $pos['preview_venue_row'] ?? []);
                            @endphp

                            @php
                                $titleAnim = $title_pos['animationType'] ?? 'none';
                                $dateAnim = $date_pos['animationType'] ?? 'none';
                                $timeAnim = $time_pos['animationType'] ?? 'none';
                                $venueAnim = $venue_pos['animationType'] ?? 'none';

                                $titleDur = ($title_pos['animationDuration'] ?? '0.8') . 's';
                                $dateDur = ($date_pos['animationDuration'] ?? '0.8') . 's';
                                $timeDur = ($time_pos['animationDuration'] ?? '0.8') . 's';
                                $venueDur = ($venue_pos['animationDuration'] ?? '0.8') . 's';
                            @endphp

                            <h4 class="ceremony-title anim-element anim-{{ $titleAnim }}" style="--tx: {{ $title_pos['transform'] ?? 'none' }}; animation-duration: {{ $titleDur }}; color: {{ $title_pos['color'] ?? ($ceremony->text_color ?? '#b02663') }}; top: {{ $title_pos['top'] }}; left: {{ $title_pos['left'] }}; transform: {{ $title_pos['transform'] ?? 'none' }}; text-align: {{ $title_pos['textAlign'] ?? 'center' }}; font-size: {{ $title_pos['fontSize'] ?? '2.2rem' }}; font-family: {{ $title_pos['fontFamily'] ?? "'Georgia', cursive, serif" }};">
                                {!! $customTexts['preview_title'] ?? $ceremony->ceramony_name !!}
                            </h4>

                            <div class="details-row date-row anim-element anim-{{ $dateAnim }}" style="--tx: {{ $date_pos['transform'] ?? 'none' }}; animation-duration: {{ $dateDur }}; color: {{ $date_pos['color'] ?? ($ceremony->details_color ?? '#2b4c5e') }}; top: {{ $date_pos['top'] }}; left: {{ $date_pos['left'] }}; transform: {{ $date_pos['transform'] ?? 'none' }}; text-align: {{ $date_pos['textAlign'] ?? 'center' }}; font-size: {{ $date_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $date_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                {!! $customTexts['preview_date_row'] ?? '<span>📅</span> ' . \Carbon\Carbon::parse($ceremony->ceramony_date)->format('l, d F Y') !!}
                            </div>

                            <div class="details-row time-row anim-element anim-{{ $timeAnim }}" style="--tx: {{ $time_pos['transform'] ?? 'none' }}; animation-duration: {{ $timeDur }}; color: {{ $time_pos['color'] ?? ($ceremony->details_color ?? '#2b4c5e') }}; top: {{ $time_pos['top'] }}; left: {{ $time_pos['left'] }}; transform: {{ $time_pos['transform'] ?? 'none' }}; text-align: {{ $time_pos['textAlign'] ?? 'center' }}; font-size: {{ $time_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $time_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                {!! $customTexts['preview_time_row'] ?? '<span>⏰</span> ' . \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') !!}
                            </div>

                            <div class="details-row venue-row anim-element anim-{{ $venueAnim }}" style="--tx: {{ $venue_pos['transform'] ?? 'none' }}; animation-duration: {{ $venueDur }}; color: {{ $venue_pos['color'] ?? ($ceremony->details_color ?? '#2b4c5e') }}; top: {{ $venue_pos['top'] }}; left: {{ $venue_pos['left'] }}; transform: {{ $venue_pos['transform'] ?? 'none' }}; text-align: {{ $venue_pos['textAlign'] ?? 'center' }}; font-size: {{ $venue_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $venue_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                @if(isset($customTexts['preview_venue_row']))
                                    {!! $customTexts['preview_venue_row'] !!}
                                @else
                                    <span>📍</span>
                                    @if($ceremony->venue)
                                        <strong>{{ $ceremony->venue->venue_name }}</strong>
                                        @if($ceremony->venue->address)
                                            <div class="venue-address" style="color: {{ $ceremony->details_color ?? '#4a5568' }};">
                                                {{ $ceremony->venue->address }}
                                            </div>
                                        @endif
                                    @else
                                        <strong>Venue to be announced</strong>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="text-align:center; color: var(--gray); padding: 40px; background: #fff; border-radius: 20px;">No ceremonies scheduled yet.</p>
                @endforelse
            </div>

        </div>
    </div>

    <div class="schedule-pane family-pane" style="margin-top: 40px;">
    <h3 class="pane-title">Host Family Members</h3>

    @if(isset($hfamily) && $hfamily && $hfamily->is_active == 1)
        @php
            // Check if a custom theme background image was selected by the host
            $familyBg = !empty($hfamily->background) && !empty($hfamily->background->image_path);
            
            $pos = is_string($hfamily->text_positions) ? json_decode($hfamily->text_positions, true) : ($hfamily->text_positions ?? []);
            $customTexts = is_string($hfamily->custom_canvas_texts) ? json_decode($hfamily->custom_canvas_texts, true) : ($hfamily->custom_canvas_texts ?? []);
        @endphp

        <div class="ceremony-card {{ $familyBg ? 'has-bg' : '' }}"
             style="
                @if($familyBg) 
                    --bg-url: url('{{ asset('storage/' . $hfamily->background->image_path) }}');
                @endif
                position: relative;
             ">

            <div class="card-content" style="position: relative; width: 100%; height: 100%;">
                @foreach(['one', 'two', 'three', 'four', 'five', 'six'] as $i => $key)
                @php 
                    $topTitle = 10 + ($i * 12);
                    $topText = 15 + ($i * 12);
                    
                    $idTitle = 'prev_title_' . $key;
                    $idText = 'prev_text_' . $key;

                    $title_pos = array_merge([
                        'top' => $topTitle.'%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.8rem', 'color' => ($hfamily->text_color ?? '#b02663')
                    ], $pos[$idTitle] ?? []);

                    $text_pos = array_merge([
                        'top' => $topText.'%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1rem', 'color' => ($hfamily->details_color ?? '#2b4c5e')
                    ], $pos[$idText] ?? []);
                    
                    $titleContent = $hfamily->{'topic_title_'.$key};
                    $textContent = $hfamily->{'text'.$key};
                @endphp
                
                @if($titleContent || isset($customTexts[$idTitle]))
                @php
                    $tAnim = $title_pos['animationType'] ?? 'none';
                    $tDur = ($title_pos['animationDuration'] ?? '0.8') . 's';
                @endphp
                <h4 class="ceremony-title anim-element anim-{{ $tAnim }}" style="--tx: {{ $title_pos['transform'] }}; animation-duration: {{ $tDur }}; position: absolute; font-family: {{ $title_pos['fontFamily'] ?? "'Georgia', cursive, serif" }}; font-weight: 600; width: max-content; color: {{ $title_pos['color'] }}; top: {{ $title_pos['top'] }}; left: {{ $title_pos['left'] }}; transform: {{ $title_pos['transform'] }}; text-align: {{ $title_pos['textAlign'] }}; font-size: {{ $title_pos['fontSize'] }};">
                    {!! $customTexts[$idTitle] ?? $titleContent !!}
                </h4>
                @endif
                
                @if($textContent || isset($customTexts[$idText]))
                @php
                    $xAnim = $text_pos['animationType'] ?? 'none';
                    $xDur = ($text_pos['animationDuration'] ?? '0.8') . 's';
                @endphp
                <div class="details-row text-row anim-element anim-{{ $xAnim }}" style="--tx: {{ $text_pos['transform'] }}; display: block !important; animation-duration: {{ $xDur }}; position: absolute; font-family: {{ $text_pos['fontFamily'] ?? "'Arial', sans-serif" }}; font-weight: 600; width: max-content; color: {{ $text_pos['color'] }}; top: {{ $text_pos['top'] }}; left: {{ $text_pos['left'] }}; transform: {{ $text_pos['transform'] }}; text-align: {{ $text_pos['textAlign'] }}; font-size: {{ $text_pos['fontSize'] }};">
                    {!! $customTexts[$idText] ?? nl2br(e($textContent)) !!}
                </div>
                @endif
                @endforeach
                
                @php
                    $idSeven = 'prev_text_seven';
                    $seven_pos = array_merge([
                        'top' => '85%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1rem', 'color' => ($hfamily->details_color ?? '#2b4c5e')
                    ], $pos[$idSeven] ?? []);
                @endphp
                
                @if($hfamily->textseven || isset($customTexts[$idSeven]))
                @php
                    $sAnim = $seven_pos['animationType'] ?? 'none';
                    $sDur = ($seven_pos['animationDuration'] ?? '0.8') . 's';
                @endphp
                <div class="details-row text-row anim-element anim-{{ $sAnim }}" style="--tx: {{ $seven_pos['transform'] }}; display: block !important; max-width: 90%; animation-duration: {{ $sDur }}; position: absolute; font-family: {{ $seven_pos['fontFamily'] ?? "'Arial', sans-serif" }}; font-weight: 600; width: max-content; color: {{ $seven_pos['color'] }}; top: {{ $seven_pos['top'] }}; left: {{ $seven_pos['left'] }}; transform: {{ $seven_pos['transform'] }}; text-align: {{ $seven_pos['textAlign'] }}; font-size: {{ $seven_pos['fontSize'] }};">
                    {!! $customTexts[$idSeven] ?? nl2br(e($hfamily->textseven)) !!}
                </div>
                @endif
            </div>
        </div>

    @else
        <p style="text-align:center; color: var(--gray, #7f8c8d); padding: 40px; background: #fff; border-radius: 20px;">
            No family host details are scheduled for this wedding event.
        </p>
    @endif
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Setup IntersectionObserver for scroll animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 // Triggers when 15% of the card is visible
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        // Optional: uncomment below to animate only once
                        // observer.unobserve(entry.target);
                    } else {
                        // Remove class if you want the animation to happen every time you scroll up/down
                        entry.target.classList.remove('in-view');
                    }
                });
            }, observerOptions);

            // Select all ceremony cards and observe them
            const cards = document.querySelectorAll('.ceremony-card');
            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>