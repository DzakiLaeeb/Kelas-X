<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ayam Goreng Joss</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Font settings */
        :root {
            --font-primary: 'Poppins', sans-serif;
            --font-size-base: 16px;
            --font-size-nav-brand: 1.8rem;
            --font-size-nav-link: 1rem;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-bold: 700;
        }

        /* Base typography */
        body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-normal);
            padding-top: 80px;
            margin: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Navbar typography */
        .navbar {
            background: var(--primary-red) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 !important;
        }

        .navbar-brand {
            font-family: var(--font-primary);
            font-size: var(--font-size-nav-brand);
            font-weight: var(--font-weight-bold);
            color: var(--primary-yellow) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            padding: 0 15px;
            height: 100%;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-family: var(--font-primary);
            font-size: var(--font-size-nav-link);
            font-weight: var(--font-weight-medium);
            color: white !important;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        /* Dropdown typography */
        .dropdown-menu {
            font-family: var(--font-primary);
        }

        .dropdown-item {
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-normal);
            padding: 8px 20px;
            color: var(--dark-brown);
            transition: all 0.3s ease;
        }

        /* Button typography */
        .btn-order-now {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-bold);
            background-color: var(--primary-yellow);
            color: var(--dark-brown);
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        /* Theme toggle typography */
        .theme-toggle {
            font-family: var(--font-primary);
            font-size: 0.9rem;
            font-weight: var(--font-weight-medium);
        }

        /* Footer typography */
        .footer {
            font-family: var(--font-primary);
        }

        .footer h5 {
            font-size: 1.1rem;
            font-weight: var(--font-weight-bold);
        }

        .footer p {
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-normal);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            :root {
                --font-size-nav-brand: 1.5rem; /* Slightly smaller on mobile */
            }

            body {
                padding-top: 80px;
            }

            .navbar {
                height: 80px;
            }
            
            .navbar-collapse {
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background: var(--primary-red);
                padding: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .nav-link {
                font-size: var(--font-size-nav-link);
                padding: 10px 15px;
            }
        }

        /* Prevent font inconsistencies in different themes */
        [data-bs-theme="dark"] {
            --font-primary: 'Poppins', sans-serif; /* Keep the same font in dark mode */
        }

        /* Ensure consistent font rendering */
        * {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        :root {
            /* Light Mode Colors */
            --primary-red: #e63946;
            --primary-yellow: #f1c40f;
            --dark-brown: #2c3e50;
            --text-color: #333;
            --text-secondary: #666;
            --bg-color: #f9f9f9;
            --card-bg: white;
            --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 10px 20px rgba(0, 0, 0, 0.15);
            --badge-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Dark Mode Colors */
        [data-bs-theme="dark"] {
            --primary-red: #ff5b69;
            --primary-yellow: #ffd54f;
            --dark-brown: #ecf0f1;
            --text-color: #ecf0f1;
            --text-secondary: #bdc3c7;
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            --card-shadow-hover: 0 10px 20px rgba(0, 0, 0, 0.4);
            --badge-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
            padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: var(--primary-red) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            height: 80px; /* Tinggi tetap */
            display: flex;
            align-items: center;
            padding: 0 !important; /* Hapus padding default */
        }
        
        .navbar .container {
            height: 100%;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-yellow) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            padding: 0 15px;
            height: 100%;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-yellow) !important;
            transform: translateY(-2px);
        }
        
        .navbar-toggler {
            border-color: var(--primary-yellow);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 183, 3, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .content-container {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            flex: 1 0 auto; /* Allow content to grow but not shrink */
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        [data-bs-theme="dark"] .content-container {
            background-color: #1e1e1e;
            color: #e0e0e0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .footer {
            background-color: var(--dark-brown);
            color: white;
            padding: 30px 0;
            margin-top: auto; /* Push footer to bottom */
            flex-shrink: 0; /* Prevent footer from shrinking */
        }
        
        .dropdown-menu {
            background-color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 8px 20px;
            color: var(--dark-brown);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-cream);
            color: var(--primary-red);
        }

        .dropdown-divider {
            border-color: var(--light-cream);
            margin: 8px 0;
        }

        .btn-order-now {
            background-color: var(--primary-yellow);
            color: var(--dark-brown);
            font-weight: 600;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-order-now:hover {
            background-color: var(--secondary-yellow);
            transform: scale(1.05);
        }
        
        /* Animated chicken icon for active page */
        .nav-item.active .nav-link::before {
            content: "🍗";
            margin-right: 5px;
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .theme-toggle-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .theme-toggle {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 2px solid var(--primary-yellow);
            border-radius: 30px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .dark-icon, .light-icon {
            margin-right: 8px;
        }

        .theme-label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Show/hide appropriate icons based on theme */
        [data-bs-theme="dark"] .dark-icon {
            display: none;
        }

        [data-bs-theme="light"] .light-icon {
            display: none;
        }

        [data-bs-theme="dark"] .theme-label {
            content: "Mode Terang";
        }

        /* Dark Mode for Home Page Components */
        [data-bs-theme="dark"] .hero-section {
            background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .hero-title {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .navbar {
            background: #181818 !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .footer {
            background-color: #181818;
        }

        [data-bs-theme="dark"] .btn-order-now {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .btn-order-now:hover {
            background-color: #ffa500;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #282828;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #333333;
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .dropdown-divider {
            border-color: #333333;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding-top: 80px; /* Tetap konsisten dengan desktop */
            }

            .navbar {
                height: 80px; /* Tetap konsisten dengan desktop */
            }
            
            .navbar-collapse {
                position: absolute;
                top: 80px; /* Sesuaikan dengan tinggi navbar */
                left: 0;
                right: 0;
                background: var(--primary-red);
                padding: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-drumstick-bite"></i> Ayam Goreng Joss
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item {{ request()->is('menu') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/menu') }}">Menu</a>
                    </li>
                    <li class="nav-item {{ request()->is('kontak') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/kontak') }}">Kontak</a>
                    </li>
                    <li class="nav-item {{ request()->is('chat') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/chat') }}">Chat</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @if(session()->has('idpelanggan'))
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="ms-1">Cart</span>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-order-now dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> {{ session('idpelanggan')['email'] }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a></li>
                                <li><a class="dropdown-item" href="{{ url('/orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i> My Orders
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <button id="darkModeToggle" class="btn btn-order-now ms-3">
                            <i class="fas fa-moon"></i>
                        </button>
                    @else
                        <a href="{{ url('/login') }}" class="btn btn-order-now me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="{{ url('/register') }}" class="btn btn-order-now me-3">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                        <button id="darkModeToggle" class="btn btn-order-now">
                            <i class="fas fa-moon"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5 content-container">
        @yield('content')
    </div>
    
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-warning">Ayam Goreng Joss</h5>
                    <p>Ayam goreng terenak dengan resep rahasia yang bikin ketagihan!</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-warning">Jam Buka</h5>
                    <p>Senin - Jumat: 10:00 - 22:00<br>
                    Sabtu - Minggu: 09:00 - 23:00</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-warning">Hubungi Kami</h5>
                    <p><i class="fas fa-phone-alt me-2"></i> 0812-3456-7890<br>
                    <i class="fas fa-envelope me-2"></i> info@ayamgorengjoss.com</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Ayam Goreng Joss by Dzexz. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        // Add active class to current menu item
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.pathname;
            const menuItems = document.querySelectorAll('.nav-link');
            menuItems.forEach(item => {
                if(item.getAttribute('href') === currentLocation) {
                    item.parentElement.classList.add('active');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const icon = darkModeToggle.querySelector('i');
            
            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            darkModeToggle.addEventListener('click', () => {
                if (document.documentElement.getAttribute('data-bs-theme') === 'dark') {
                    document.documentElement.removeAttribute('data-bs-theme');
                    localStorage.setItem('darkMode', 'disabled');
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                } else {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('darkMode', 'enabled');
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            });
        });
    </script>
</body>
</html>
