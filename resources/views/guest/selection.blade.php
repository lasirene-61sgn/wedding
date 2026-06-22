<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Invitation</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --pink-primary: #d63384;
            --pink-light: rgba(255, 245, 248, 0.85);
            --gold: #c5a059;
            --dark: #2d2d2d;
            --gray: #666;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Dynamic background image with fallback */
            background: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow-x: hidden;
        }

        /* Overlay to make text readable */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(214, 51, 132, 0.2) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: -1;
        }

        .selection-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            text-align: center;
            z-index: 1;
        }

        .selection-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            text-align: left;
            border: 1px solid rgba(255,255,255,0.5);
            animation: fadeInUp 0.8s ease-out backwards;
        }

        .selection-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(214, 51, 132, 0.15);
            border-color: rgba(214, 51, 132, 0.3);
        }

        /* Stagger animation for multiple cards */
        .selection-card:nth-child(1) { animation-delay: 0.1s; }
        .selection-card:nth-child(2) { animation-delay: 0.3s; }
        .selection-card:nth-child(3) { animation-delay: 0.5s; }
        .selection-card:nth-child(4) { animation-delay: 0.7s; }

        h2.title {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: #b02a6c;
            margin: 0 0 10px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);
            animation: fadeInDown 0.8s ease-out;
        }

        .subtitle {
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 35px;
            text-align: center;
            font-size: 1.1rem;
            animation: fadeIn 1s ease-out 0.4s backwards;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }

        .couple-names {
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--dark);
            margin: 8px 0 15px;
            background: linear-gradient(45deg, var(--dark), var(--pink-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Status Badges Styling */
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
            background: linear-gradient(135deg, var(--pink-primary), #ff6b6b);
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
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-view::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.5s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(214, 51, 132, 0.4);
        }

        .btn-view:hover::after {
            left: 100%;
        }

        .alert-info {
            background: rgba(209, 236, 241, 0.9);
            color: #0c5460;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid #bee5eb;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.5s ease-out;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .selection-container {
                padding: 15px;
            }
            .selection-card {
                padding: 25px;
                border-radius: 20px;
            }
            h2.title {
                font-size: 2.8rem;
            }
            .couple-names {
                font-size: 1.3rem;
            }
            .btn-view {
                padding: 12px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="selection-container">
        <h2 class="title">My Invitations</h2>
        <p class="subtitle">Select a wedding to view details</p>

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

            <div style="color: var(--gold); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1.5px; font-weight: 700;">Host</div>
            <div class="couple-names">{{ $invite->host->name }}</div>

            <p style="font-size: 0.95rem; color: var(--gray); margin-bottom: 20px; line-height: 1.5;">
                Celebrating the wedding of<br>
                <strong style="color: var(--dark);">{{ $invite->host->bride_name }}</strong> & <strong style="color: var(--dark);">{{ $invite->host->groom_name }}</strong>
            </p>

            <a href="{{ route('guest.wedding.details', $invite->id) }}" class="btn-view">
                Open Invitation Dashboard
            </a>
        </div>
        @endforeach
        @else
        <div class="selection-card" style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;">💌</div>
            <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 20px;">No invitations found for your number.</p>
            <a href="{{ route('guest.login') }}" style="color: var(--pink-primary); text-decoration: none; font-weight: 600; border-bottom: 2px solid var(--pink-primary); padding-bottom: 2px; transition: opacity 0.3s;">Try a different number</a>
        </div>
        @endif
    </div>

</body>

</html>