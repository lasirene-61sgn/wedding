<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Invitation</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .selection-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            text-align: center;
        }

        .selection-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.3s;
            position: relative;
            text-align: left;
            /* Aligns content beautifully */
        }

        .selection-card:hover {
            transform: translateY(-5px);
        }

        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 3rem;
            color: var(--pink-primary);
            margin: 0 0 10px;
            text-align: center;
        }

        .couple-names {
            font-weight: 600;
            font-size: 1.3rem;
            color: var(--dark);
            margin: 5px 0 10px;
        }

        /* Status Badges Styling */
        .status-badge-container {
            margin-bottom: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .btn-view {
            display: inline-block;
            background: var(--pink-primary);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            margin-top: 10px;
            transition: opacity 0.2s;
        }

        .btn-view:hover {
            opacity: 0.9;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="selection-container">
        <h2>My Invitations</h2>
        <p style="color: var(--gray); margin-bottom: 30px; text-align: center;">Select a wedding to view details</p>

        @if(session('info'))
        <div class="alert-info">{{ session('info') }}</div>
        @endif

        @if(isset($invitations) && $invitations->count() > 0)
        @foreach($invitations as $invite)
        <div class="selection-card">
            <!-- Dynamic RSVP Status Badges Added Here -->
            <div class="status-badge-container">
                @php $status = $invite->status ?? 'pending'; @endphp

                @if($status == 'pending')
                <span class="status-badge status-pending">⏳ Response Pending</span>
                @elseif($status == 'accepted')
                <span class="status-badge status-accepted">✓ Attending</span>
                @else
                <span class="status-badge status-declined">✗ Declined</span>
                @endif
            </div>

            <div style="color: var(--gold); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; font-weight: 600;">Host</div>
            <div class="couple-names">{{ $invite->host->name }}</div>

            <p style="font-size: 0.9rem; color: var(--gray); margin-bottom: 15px;">
                Celebrating the wedding of <strong>{{ $invite->host->bride_name }}</strong> & <strong>{{ $invite->host->groom_name }}</strong>
            </p>

            <a href="{{ route('guest.wedding.details', $invite->id) }}" class="btn-view">
                Open Invitation Dashboard
            </a>
        </div>
        @endforeach
        @else
        <div class="selection-card" style="text-align: center;">
            <p style="color: var(--gray);">No invitations found for your number.</p>
            <a href="{{ route('guest.login') }}" style="color: var(--pink-primary); text-decoration: none; font-weight: 600;">Try a different number</a>
        </div>
        @endif
    </div>
    <!-- TEMPORARY DEBUGGING BLOCK: Remove this after finding the issue -->
    <!-- <div style="background: #333; color: #fff; padding: 15px; margin-bottom: 20px; text-align: left; font-family: monospace; border-radius: 10px; font-size: 0.85rem;">
    <strong>Debug Session Data:</strong><br>
    • Session 'guest_phone' value: <span style="color: #ff9f43;">"{{ session('guest_phone') }}"</span><br>
    • Invitations Count from Controller: <span style="color: #ff9f43;">{{ isset($invitations) ? $invitations->count() : 'Not Set' }}</span><br>
    <br>
    <strong>Troubleshooting Step:</strong> Check your database table. Does a row exist where the column <code>guest_number</code> matches the orange value above exactly?
</div> -->

</body>

</html>