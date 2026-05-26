<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Invitation Portal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #d4af37;
            border-radius: 15px 15px 0 0;
        }

        .container h2 {
            font-family: 'Great Vibes', cursive;
            color: #d4af37;
            font-size: 3.5rem;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .container p.subtitle {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .invitation-card {
            background: #fff;
            border: 1px solid #eee;
            padding: 30px;
            border-radius: 10px;
            text-align: left;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
        }

        .invitation-card::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #d4af37;
        }

        .invitation-card h3 {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .details-box {
            background: #fafafa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            color: #444;
            line-height: 1.6;
        }

        .status-wrapper {
            margin-bottom: 25px;
            display: block;
        }

        .status-label {
            font-size: 0.9rem;
            color: #777;
            margin-right: 5px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-accepted { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-declined { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .action-form-group {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .btn {
            flex: 1;
            display: inline-block;
            padding: 14px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: opacity 0.2s ease;
        }

        .btn-decline { background-color: #dc3545; }
        .btn-reset { background-color: #6c757d; }
        .btn:hover { opacity: 0.9; }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .action-form-group { flex-direction: column; }
            .container { padding: 25px 15px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Hello, {{ $guest->guest_name }}!</h2>
        <p class="subtitle">You are cordially invited to celebrate with us</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="invitation-card">
            <h3>Wedding Celebration Events</h3>
            
            <div class="details-box">
                <strong>Assigned Ceremonies:</strong><br>
                {{ $guest->assigned_ceremonies ?? ($guest->ceramony->ceramony_name ?? 'Wedding Main Ceremony') }}
            </div>
            
            <div class="status-wrapper">
                <span class="status-label">Your Current RSVP Status:</span>
                <!-- Fallback to checking an rsvp_status string on the GuestList record -->
                @php $status = $guest->rsvp_status ?? 'pending'; @endphp
                
                @if($status == 'pending')
                    <span class="status-badge status-pending">Pending Response</span>
                @elseif($status == 'accepted')
                    <span class="status-badge status-accepted">Attending ✓</span>
                @else
                    <span class="status-badge status-declined">Declined ✗</span>
                @endif
            </div>
            
            <!-- Conditional Forms based on current record choice status fields -->
            @if($status == 'pending')
                <p style="margin-bottom: 12px; font-size: 0.9rem; color: #555; font-weight: 500;">Kindly confirm your attendance:</p>
                <div class="action-form-group">
                    <form action="{{ route('guest.rsvp.update', $guest->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="rsvp_status" value="accepted">
                        <button type="submit" class="btn">Accept Invitation</button>
                    </form>

                    <form action="{{ route('guest.rsvp.update', $guest->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="rsvp_status" value="declined">
                        <button type="submit" class="btn btn-decline">Decline</button>
                    </form>
                </div>
            @else
                <div style="text-align: center;">
                    <form action="{{ route('guest.rsvp.update', $guest->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="rsvp_status" value="pending">
                        <button type="submit" class="btn btn-reset">Change My RSVP Choice</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

</body>
</html>