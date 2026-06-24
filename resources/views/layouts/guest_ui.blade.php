<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wedding Invitation')</title>
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --pink-primary: #d63384;
            --pink-dark: #b02663;
            --pink-light: #fff5f8;
            --gold: #d4af37;
            --gold-dark: #b5952f;
            --gold-light: #f9f6e8;
            --dark: #2d3436;
            --gray: #636e72;
            --bg-warm: #fdfbf9;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-warm);
            /* Optional background pattern or texture */
            background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Glassmorphism Utilities */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            border-radius: 24px;
        }

        /* Top Header Layout */
        header.wedding-header {
            padding: 40px 20px 20px;
            text-align: center;
            background: linear-gradient(to bottom, rgba(255,255,255,1), rgba(255,255,255,0));
            position: relative;
            z-index: 10;
        }

        .decorative-ornament {
            font-size: 1.5rem;
            color: var(--gold);
            margin-bottom: 5px;
            letter-spacing: 4px;
            opacity: 0.8;
        }

        header.wedding-header h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: var(--pink-primary);
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
            animation: fadeInDown 1s ease-out;
        }

        header.wedding-header p {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--gray);
            margin-top: 5px;
            font-style: italic;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        /* FLOATING PILL NAV BAR */
        .nav-bar-container {
            position: sticky;
            top: 15px;
            z-index: 1000;
            padding: 0 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            animation: fadeIn 1s ease-out 0.4s both;
        }

        .nav-bar {
            display: flex;
            gap: 5px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .nav-bar a {
            text-decoration: none;
            color: var(--gray);
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 18px;
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

        /* Main Content Area */
        main.main-content {
            flex: 1;
            padding: 0 20px 80px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Forms & Buttons */
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: rgba(255,255,255,0.9);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
        }

        .btn-primary-wedding {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary-wedding:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
            color: white;
        }

        .btn-outline-wedding {
            background: transparent;
            color: var(--pink-primary);
            border: 2px solid var(--pink-primary);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-wedding:hover {
            background: var(--pink-primary);
            color: white;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            header.wedding-header h1 { font-size: 2.8rem; }
            .nav-bar a { font-size: 0.75rem; padding: 8px 12px; }
            .nav-bar { gap: 2px; }
        }

    </style>
    @stack('styles')
</head>
<body>

    @yield('header')

    <!-- Global Floating Action for Login / Switch Account -->
    <a href="{{ route('guest.login') }}" class="btn-outline-wedding" style="position: absolute; top: 20px; right: 20px; z-index: 2000; font-size: 0.75rem; padding: 6px 15px;">Login</a>

    <main class="main-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
