<!DOCTYPE html>
<html lang="id" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Toko Online</title>
    <link rel="icon" type="jpg" href="https://www.shutterstock.com/image-vector/atk-letter-design-technology-logo-260nw-2384732247.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Theme Variables */
        :root {
            /* Blue and White Theme */
            --bg-color: #f0f8ff; /* Light blue background */
            --card-bg: #ffffff;
            --text-color: #2c3e50;
            --text-secondary: #6c757d;
            --primary-color: #1e88e5; /* Vibrant blue */
            --primary-hover: #1565c0; /* Darker blue for hover */
            --secondary-color: #e3f2fd; /* Very light blue */
            --accent-color: #2196f3; /* Another blue shade */
            --danger-color: #e74c3c;
            --header-bg: #ffffff;
            --footer-bg: #1a237e; /* Dark blue footer */
            --border-color: #bbdefb; /* Light blue border */
            --shadow-sm: 0 4px 6px rgba(30, 136, 229, 0.1);
            --shadow-md: 0 6px 12px rgba(30, 136, 229, 0.15);
            --shadow-lg: 0 10px 25px rgba(30, 136, 229, 0.2);
            --hover-bg: #e3f2fd;
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --bg-color: #0a1929; /* Dark blue background */
            --card-bg: #102a43;
            --text-color: #f8f9fa;
            --text-secondary: #adb5bd;
            --primary-color: #42a5f5; /* Lighter blue for dark mode */
            --primary-hover: #64b5f6;
            --secondary-color: #1a3a5f;
            --accent-color: #29b6f6;
            --danger-color: #e74c3c;
            --header-bg: #0d2137;
            --footer-bg: #051e34;
            --border-color: #1e4976;
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.25);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.3);
            --hover-bg: #1a3a5f;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

        /* Enhanced Header */
        header {
            background-color: var(--header-bg);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }

        header.shrink {
            padding: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header.shrink .logo {
            font-size: 1.4rem;
        }

        header.shrink .nav-menu {
            font-size: 0.95rem;
        }

        header.shrink .auth-nav .btn {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }

        header.shrink .user-greeting {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            letter-spacing: -0.5px;
        }

        .logo:hover {
            color: var(--primary-hover);
            transform: scale(1.05);
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
            justify-content: center;
        }

        .nav-menu a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover {
            color: var(--primary-color);
            background-color: var(--hover-bg);
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .nav-menu a i {
            margin-right: 0.5rem;
        }

        .auth-nav {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .auth-nav a {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-link {
            position: relative;
            color: var(--text-color);
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .cart-link:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .cart-count {
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            margin-left: 1px;
            position: relative;
            top: -15px;
        }

        /* User Menu */
        .user-greeting {
            font-weight: 600;
            color: var(--text-color);
            background-color: var(--secondary-color);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-greeting:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .dropdown-item {
            color: var(--text-color);
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
        }

        .dropdown-divider {
            border-top: 1px solid var(--border-color);
            margin: 0.5rem 0;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-danger:hover {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color) !important;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            border: 2px solid var(--primary-color);
            border-radius: 30px;
            cursor: pointer;
            background-color: transparent;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-icon {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background-color: var(--hover-bg);
            transform: translateY(-2px);
        }

        /* Search Container */
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .search-container form {
            display: flex;
            width: 100%;
        }

        .search-input {
            flex: 1;
            margin: 0;
            padding: 1.2rem 1.5rem;
            border: 2px solid #3498db;
            border-radius: 30px 0 0 30px;
            outline: none;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            min-width: 0; /* Allow input to shrink */
        }

        .search-button {
            padding: 1.2rem 2rem;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .search-button:hover {
            background: linear-gradient(45deg, #2980b9, #2573a7);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
        }

        /* Dark Mode Compatibility */
        [data-theme="dark"] .search-container {
            background: rgba(44, 62, 80, 0.95);
        }

        [data-theme="dark"] .search-input {
            color: #ecf0f1;
            border-color: #34495e;
            background: transparent;
        }

        [data-theme="dark"] .search-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .nav-menu {
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-wrap: wrap;
            }

            .nav-menu {
                order: 3;
                width: 100%;
                justify-content: space-between;
                margin-top: 1rem;
                gap: 0.5rem;
            }

            .search-container {
                flex-direction: column;
                gap: 1rem;
            }

            .search-container form {
                flex-direction: column;
                gap: 1rem;
            }

            .search-input {
                width: 100%;
                border-radius: 30px;
            }

            .search-button {
                width: 100%;
                border-radius: 30px;
            }
        }

        @media (max-width: 576px) {
            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-menu a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .auth-nav {
                margin-left: 0;
            }

            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .search-input,
            .search-button {
                padding: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="nav-container">
                <a href="{{ route('home') }}" class="logo">TokoATK</a>
                <nav class="nav-menu">
                    <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                    <a href="{{ route('shop') }}"><i class="fas fa-shopping-cart"></i> Produk</a>
                    <a href="{{ route('tentangkami') }}"><i class="fas fa-info-circle"></i> Tentang Kami</a>
                    <a href="{{ route('hubungikami') }}"><i class="fas fa-envelope"></i> Hubungi Kami</a>
                </nav>

                <div class="auth-nav">
                    <a href="{{ route('cart') }}"><i class="fas fa-shopping-basket cart-icon"></i>
                        @if(session()->has('cart_count') && session('cart_count') > 0)
                            <span class="cart-count">{{ session('cart_count') }}</span>
                        @endif
                    </a>
                    <button id="darkModeToggle" class="btn-icon">
                        <i class="fas fa-moon"></i>
                    </button>
                    @if(auth()->check())
                        <div class="dropdown">
                            <button class="btn dropdown-toggle user-greeting" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Halo, {{ auth()->user()->name }}!
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                @if(auth()->user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fas fa-shopping-bag me-2"></i>Pesanan</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn">Masuk</a>
                        <a href="{{ route('register') }}" class="btn">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    @if(!Request::is('tentangkami') && !Request::is('hubungikami'))
    <div class="search-container">
        <form action="{{ route('search') }}" method="POST" id="searchForm">
            @csrf
            <input type="text" name="search" class="search-input" placeholder="Cari produk..." value="{{ $searchQuery ?? '' }}">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>
    @endif

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;

            // Check for saved theme preference or respect OS preference
            const currentTheme = localStorage.getItem('theme');
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

            const html = document.documentElement;

            if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
                body.setAttribute('data-theme', 'dark');
                html.setAttribute('data-bs-theme', 'dark');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else if (currentTheme === 'light' || (!currentTheme && !prefersDarkScheme.matches)) {
                body.removeAttribute('data-theme');
                html.setAttribute('data-bs-theme', 'light');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }

            // Toggle dark mode
            darkModeToggle.addEventListener('click', function() {
                if (body.getAttribute('data-theme') === 'dark') {
                    body.removeAttribute('data-theme');
                    html.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                } else {
                    body.setAttribute('data-theme', 'dark');
                    html.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            });

            // Listen for OS theme changes
            prefersDarkScheme.addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        body.setAttribute('data-theme', 'dark');
                        html.setAttribute('data-bs-theme', 'dark');
                        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                    } else {
                        body.removeAttribute('data-theme');
                        html.setAttribute('data-bs-theme', 'light');
                        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                    }
                }
            });
        });

        // Shrinking navbar functionality
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const logo = document.querySelector('.logo');
            const navMenu = document.querySelector('.nav-menu');

            if (window.scrollY > 50) {
                header.classList.add('shrink');
            } else {
                header.classList.remove('shrink');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
