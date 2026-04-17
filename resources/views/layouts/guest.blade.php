<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #ffffff; /* Pure White */
            --text-main: #2d3748;   /* Dark Grey for readability */
            --text-muted: #718096; /* Light Grey for icons/subtext */
            --accent-color: #4a5568; /* Hover color */
            --active-bg: #f7fafc;  /* Very light blue-grey for active link */
        }

        body {
            background-color: #f8f9fa;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            background: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0; /* Subtle border */
            transition: all 0.3s ease;
            padding: 1.5rem 1rem;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
            padding: 0 1rem 2rem 1rem;
            display: block;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.8rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: 0.2s;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .nav-link:hover {
            background-color: var(--active-bg);
            color: var(--text-main);
        }

        .nav-link.active {
            background-color: #edf2f7;
            color: #2b6cb0; /* Professional Blue for active state */
        }

        /* Logout Button - Keeps the red but cleaner */
        .btn-logout {
            background-color: #fff5f5;
            color: #c53030;
            border: 1px solid #feb2b2;
            width: 100%;
            padding: 0.6rem;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background-color: #c53030;
            color: white;
        }

        /* Content Wrapper */
        #main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Mobile Adjustments */
        @media (max-width: 991.98px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            #main-content {
                margin-left: 0;
            }
            #sidebar.active {
                left: 0;
                box-shadow: 15px 0 30px rgba(0,0,0,0.05);
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.3);
                z-index: 999;
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Hide Toggle on PC */
        @media (min-width: 992px) {
            #mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="overlay"></div>

    <nav id="sidebar">
        <a href="#" class="sidebar-brand">Guest PANEL</a>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-people-fill me-3"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-gear-fill me-3"></i> Settings
                </a>
            </li>
        </ul>

        
    </nav>

    <div id="main-content">
        <nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3 sticky-top">
            <button class="btn btn-light border" id="mobile-toggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto">
                <span class="fw-semibold">Hello, Guest</span>
            </div>
        </nav>

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('mobile-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('show');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>