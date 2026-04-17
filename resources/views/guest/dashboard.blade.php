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
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .nav-bar a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .nav-bar a:hover, .nav-bar a.active {
            color: var(--pink-primary);
        }

        /* Main Content */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 20px 100px; /* Extra bottom padding for mobile */
        }

        .section-title {
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            margin-bottom: 20px;
            text-align: center;
        }

        /* Ceremony Cards */
        .ceremony-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
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

        .card-content h4 {
            margin: 0 0 10px 0;
            color: var(--pink-primary);
            font-size: 1.4rem;
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

        /* Map Section */
        .map-card {
            background: #ffeef4;
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            border: 2px dashed var(--pink-primary);
        }

        .btn-map {
            display: inline-block;
            background: var(--pink-primary);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
        }

        /* Responsive Fixes */
        @media (max-width: 480px) {
            header h1 { font-size: 2.5rem; }
            .card-image { height: 150px; }
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
    <a href="{{ route('guest.profile.edit', $invite->id) }}">👤 My Profile</a> <a href="{{ route('guest.gallery', $invite->id) }}">📸 Gallery</a>
</div>
<div class="container">
    <h3 class="section-title">Ceremony Schedule</h3>

    @forelse($detailedCeremonies as $ceremony)
        <div class="ceremony-card">
            @if($ceremony->ceramony_image)
                <img src="{{ asset('storage/' . $ceremony->ceramony_image) }}" class="card-image" alt="{{ $ceremony->ceramony_name }}">
            @else
                <div style="height:150px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:3rem;">💍</div>
            @endif

            <div class="card-content">
                <h4 style="margin: 0 0 10px 0; color: #d63384;">{{ $ceremony->ceramony_name }}</h4>
                
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
                    <div class="venue-address" style="padding-left: 28px; font-size: 0.85rem; color: #777; font-style: italic;">
                        {{ $ceremony->venue->address }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p style="text-align:center;">No ceremonies scheduled yet.</p>
    @endforelse
</div>

</body>
</html>