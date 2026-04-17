<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Login</title>
    
    <!-- Importing Google Fonts for a Wedding Look -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* --- General Reset & Background --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            /* Elegant background: You can replace this URL with a wedding photo */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px; /* Ensures spacing on very small mobile screens */
        }

        /* --- The Login Box --- */
        .login-box {
            background-color: #ffffff;
            width: 100%;
            max-width: 400px; /* Limits width on desktop */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }

        /* Decorative top border */
        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #d4af37; /* Gold color */
            border-radius: 15px 15px 0 0;
        }

        /* --- Typography --- */
        .login-box h2 {
            font-family: 'Great Vibes', cursive; /* Cursive font for elegance */
            color: #d4af37; /* Gold */
            font-size: 3rem;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .login-box p.subtitle {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        /* Input Focus Effect */
        .form-group input:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.2);
        }

        /* --- Button --- */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #d4af37;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #b5952f;
            transform: translateY(-2px);
        }

        /* --- Error Message --- */
        .error-message {
            background-color: #ffe6e6;
            color: #d63031;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 0.9rem;
            border: 1px solid #ffcccc;
        }

        /* --- Responsive Media Queries --- */
        
        /* Tablets and Smaller Laptops */
        @media (max-width: 768px) {
            .login-box {
                padding: 30px;
            }
            .login-box h2 {
                font-size: 2.5rem;
            }
        }

        /* Mobile Devices */
        @media (max-width: 480px) {
            body {
                padding: 10px;
                align-items: flex-start; /* Moves box slightly up on small screens */
                padding-top: 50px;
            }
            .login-box {
                padding: 25px;
                border-radius: 10px;
            }
            .login-box h2 {
                font-size: 2.2rem;
            }
            .form-group input {
                padding: 10px; /* Slightly smaller inputs for mobile */
            }
            button[type="submit"] {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Wedding Guest Login</h2>
        <p class="subtitle">Please enter your details to view the invitation</p>
        
        <form action="{{ route('guest.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter Registered Number" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
            </div>

            <button type="submit">View Invitations</button>
        </form>
        
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
    </div>

</body>
</html>