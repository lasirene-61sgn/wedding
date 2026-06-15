<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Dashboard | {{ $invite->host->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #d63384;
            --pink-light: #fff5f8;
            --gold: #c5a059;
            --dark: #2d2d2d;
            --gray: #777;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--pink-light);
            margin: 0;
            padding: 0;
            color: var(--dark);
        }

        /* Header Section */
        header {
            background: white;
            padding: 40px 20px;
            text-align: center;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        header h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: var(--pink-primary);
            margin: 0;
        }

        header p {
            font-size: 1rem;
            color: var(--gray);
            margin-top: 5px;
        }

        /* Sticky Navigation */
        .nav-bar {
            display: flex;
            justify-content: space-around;
            background: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .nav-bar a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .nav-bar a:hover,
        .nav-bar a.active {
            color: var(--pink-primary);
        }

        /* Main Content Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 20px 100px;
        }

        /* Invitation & Status Card Styling */
        .invitation-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .invitation-card h3 {
            margin-top: 0;
            color: var(--dark);
        }

        .details-box {
            background: var(--pink-light);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .status-wrapper {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #ffeeba;
            color: #856404;
        }

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-declined {
            background: #f8d7da;
            color: #721c24;
        }

        .action-form-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Buttons */
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

        .btn:hover {
            opacity: 0.9;
        }

        .btn-decline {
            background: #6c757d;
        }

        .btn-reset {
            background: transparent;
            color: var(--pink-primary);
            border: 2px solid var(--pink-primary);
        }

        .alert-success {
            padding: 12px;
            background: #d4edda;
            color: #155724;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        /* Ceremony Schedules */
        .section-title {
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            margin: 40px 0 20px;
            text-align: center;
        }

        .ceremony-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .ceremony-card:hover {
            transform: translateY(-5px);
        }

        .card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .details-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--dark);
        }

        .details-row span {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .venue-address {
            font-size: 0.85rem;
            color: var(--gray);
            font-style: italic;
            margin-top: 5px;
            padding-left: 28px;
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 2.5rem;
            }

            .card-image {
                height: 150px;
            }

            .action-form-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>{{ $invite->host->name }}</h1>
        <p>Celebrating the Union of <br><strong>{{ $invite->host->bride_name }} & {{ $invite->host->groom_name }}</strong></p>
    </header>

    <div class="nav-bar">
        <a href="{{ route('guest.select') }}">↩ Invitations</a>
        <a href="#" class="active">🏠 Home</a>
        <a href="{{ route('guest.profile.edit', $invite->id) }}">👤 My Profile</a>
        <a href="{{ route('guest.gallery', $invite->id) }}">📸 Gallery</a>
    </div>

    <div class="container">

        <h2 style="text-align: center; color: var(--dark); margin-bottom: 5px;">Hello, {{ $invite->guest_name }}!</h2>
        <p style="text-align: center; color: var(--gray); margin-bottom: 25px; font-style: italic;">You are cordially invited to celebrate with us</p>

        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="invitation-card">
            <h3>Wedding RSVP Status</h3>

            <div class="details-box">
                <strong>Your Assigned Ceremonies:</strong><br>
                {{ $invite->assigned_ceremonies ?? 'Wedding Main Ceremony' }}
            </div>

            <div class="status-wrapper">
                <span class="status-label">Your RSVP Choice:</span>
                {{-- Your updateStatus method looks at the column named 'status' --}}
                @php $status = $invite->status ?? 'pending'; @endphp

                @if($status == 'pending')
                <span class="status-badge status-pending">Pending Response</span>
                @elseif($status == 'accepted')
                <span class="status-badge status-accepted">Attending ✓</span>
                @else
                <span class="status-badge status-declined">Declined ✗</span>
                @endif
            </div>

           <!-- Inside resources/views/guest/dashboard.blade.php -->
@if($status == 'pending')
    <p style="margin-bottom: 12px; font-size: 0.9rem; color: #555; font-weight: 500;">Kindly confirm your attendance:</p>
    <div class="action-form-group">
        <!-- FIXED ROUTE NAME HERE -->
        <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="flex:1;">
            @csrf
            <input type="hidden" name="status" value="accepted">
            <button type="submit" class="btn">Accept Invitation</button>
        </form>

        <!-- FIXED ROUTE NAME HERE -->
        <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="flex:1;">
            @csrf
            <input type="hidden" name="status" value="declined">
            <button type="submit" class="btn btn-decline">Decline</button>
        </form>
    </div>
@else
    <div style="text-align: center;">
        <!-- FIXED ROUTE NAME HERE -->
        <form action="{{ route('guest.update_status', $invite->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="pending">
            <button type="submit" class="btn btn-reset">Change My RSVP Choice</button>
                </form>
            </div>
@endif
        </div>

        <h3 class="section-title">Ceremony Schedule</h3>

        @forelse($detailedCeremonies as $ceremony)
        <div class="ceremony-card">
            @if($ceremony->ceramony_image)
            <img src="{{ asset('storage/' . $ceremony->ceramony_image) }}" class="card-image" alt="{{ $ceremony->ceramony_name }}">
            @else
            <div style="height:150px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:3rem;">💍</div>
            @endif

            <div class="card-content">
                <h4 style="margin: 0 0 10px 0; color: var(--pink-primary); font-size: 1.4rem;">{{ $ceremony->ceramony_name }}</h4>

                <div class="details-row">
                    <span>📅</span> {{ \Carbon\Carbon::parse($ceremony->ceramony_date)->format('l, d F Y') }}
                </div>

                <div class="details-row">
                    <span>⏰</span> {{ \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') }}
                </div>

                <div class="details-row">
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
        <p style="text-align:center; color: var(--gray);">No ceremonies scheduled yet.</p>
        @endforelse
    </div>

</body>

</html>