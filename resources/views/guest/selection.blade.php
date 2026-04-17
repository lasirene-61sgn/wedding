<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invitations</title>
    
    <!-- Importing Google Fonts (Same as Login Page) -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* --- General Reset & Background (Matches Login Page) --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            /* Same background as login for consistency */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Parallax effect on desktop */
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* --- Main Container --- */
        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 700px; /* Wider than login box to fit list */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }

        /* Decorative top border (Gold) */
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

        /* --- Typography --- */
        .container h2 {
            font-family: 'Great Vibes', cursive;
            color: #d4af37;
            font-size: 3.5rem;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .container p.subtitle {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 40px;
            font-weight: 500;
        }

        /* --- Invitation List --- */
        .invitation-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* --- Invitation Card --- */
        .invitation-card {
            background: #fff;
            border: 1px solid #eee;
            padding: 25px;
            border-radius: 10px;
            text-align: left;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .invitation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #d4af37;
        }

        /* Left accent border on card */
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
            font-family: 'Montserrat', sans-serif;
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* --- Status Badge --- */
        .status-wrapper {
            margin-bottom: 20px;
            display: inline-block;
        }

        .status-label {
            font-size: 0.85rem;
            color: #777;
            margin-right: 5px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Status Colors */
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .status-accepted {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-declined {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* --- Buttons --- */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #d4af37;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #b5952f;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-link {
            background: none;
            color: #777;
            padding: 10px 0;
            text-decoration: underline;
            text-transform: none;
            letter-spacing: 0;
            font-size: 0.9rem;
        }

        .btn-link:hover {
            color: #333;
            background: none;
        }

        /* --- Empty State (Optional) --- */
        .empty-state {
            color: #777;
            font-style: italic;
        }

        /* --- Responsive Media Queries --- */
        
        /* Tablets */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }
            .container h2 {
                font-size: 3rem;
            }
        }

        /* Mobile Devices */
        @media (max-width: 480px) {
            body {
                padding: 20px 15px;
            }
            .container {
                padding: 25px 20px;
            }
            .container h2 {
                font-size: 2.5rem;
            }
            .invitation-card {
                padding: 20px;
            }
            .invitation-card h3 {
                font-size: 1.2rem;
            }
            .btn {
                width: 100%; /* Full width buttons on mobile for easier tapping */
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome!</h2>
        <p class="subtitle">You have {{ $invitations->count() }} Invitation(s)</p>
        
        <div class="invitation-list">
            @forelse($invitations as $invite)
                <div class="invitation-card">
                    <h3>{{ $invite->host->name }}'s Wedding</h3>
                    
                    <div class="status-wrapper">
                        <span class="status-label">Status:</span>
                        @if($invite->status == 'pending')
                            <span class="status-badge status-pending">{{ ucfirst($invite->status) }}</span>
                        @elseif($invite->status == 'accepted')
                            <span class="status-badge status-accepted">{{ ucfirst($invite->status) }}</span>
                        @else
                            <span class="status-badge status-declined">{{ ucfirst($invite->status) }}</span>
                        @endif
                    </div>
                    
                    <div class="action-group">
                        @if($invite->status == 'pending')
                            <a href="{{ route('guest.save_the_date', $invite->id) }}" class="btn">View Invitation</a>
                        @elseif($invite->status == 'accepted')
                            <a href="{{ route('guest.wedding.details', $invite->id) }}" class="btn btn-success">Enter Wedding</a>
                        @else
                            <p style="color:#777; font-size: 0.9rem; margin-bottom: 10px;">Invitation Declined</p>
                            <a href="{{ route('guest.save_the_date', $invite->id) }}" class="btn btn-link">Change Decision</a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="empty-state">No invitations found.</p>
            @endforelse
        </div>
    </div>

</body>
</html>