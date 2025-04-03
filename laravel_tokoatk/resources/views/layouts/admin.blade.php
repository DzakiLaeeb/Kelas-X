<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - {{ config('app.name', 'TokoATK') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            /* Light Theme */
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --sidebar-width: 250px;
            --header-height: 60px;
            --bg-color: #f8f9fa;
            --text-color: #333;
            --card-bg: #fff;
            --border-color: #dee2e6;
            --input-bg: #fff;
            --input-text: #333;
            --table-bg: #fff;
            --table-border: #dee2e6;
            --table-hover: #f8f9fa;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
            --sidebar-hover: #34495e;
            --header-bg: #fff;
            --header-text: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-color: #2c3e50;
            --dark-color: #ecf0f1;
            --bg-color: #121212;
            --text-color: #f8f9fa;
            --card-bg: #1e1e1e;
            --border-color: #343a40;
            --input-bg: #2c2c2c;
            --input-text: #f8f9fa;
            --table-bg: #1e1e1e;
            --table-border: #343a40;
            --table-hover: #2c2c2c;
            --sidebar-bg: #1a1a1a;
            --sidebar-text: #f8f9fa;
            --sidebar-hover: #2c2c2c;
            --header-bg: #1a1a1a;
            --header-text: #f8f9fa;
            --shadow-color: rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 1rem 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-heading {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sidebar-text);
            opacity: 0.7;
            padding: 1rem 1.5rem 0.5rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background-color: var(--sidebar-hover);
            color: var(--sidebar-text);
        }

        .sidebar-item.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar-icon {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 1rem;
            transition: all 0.3s ease;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: var(--card-bg);
            color: var(--text-color);
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px var(--shadow-color);
            margin-bottom: 2rem;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .user-dropdown-toggle:hover {
            background-color: #f8f9fa;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .user-name {
            font-weight: 500;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            background-color: var(--card-bg);
            color: var(--text-color);
            box-shadow: 0 2px 10px var(--shadow-color);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
            color: var(--text-color);
            background-color: var(--table-bg);
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        .table th {
            font-weight: 600;
            border-top: none;
            border-color: var(--table-border);
        }

        .table td {
            border-color: var(--table-border);
        }

        .table-hover tbody tr:hover {
            background-color: var(--table-hover);
        }

        /* Forms */
        .form-label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            border-color: var(--primary-color);
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">TokoATK Admin</a>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <div class="sidebar-heading">Management</div>

            <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </a>

            <a href="{{ route('admin.banners.index') }}" class="sidebar-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fas fa-images sidebar-icon"></i>
                <span class="sidebar-text">Banners</span>
            </a>

            <div class="sidebar-heading">Account</div>

            <a href="{{ route('home') }}" class="sidebar-item">
                <i class="fas fa-store sidebar-icon"></i>
                <span class="sidebar-text">Visit Store</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="sidebar-item border-0 w-100 text-start">
                    <i class="fas fa-sign-out-alt sidebar-icon"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <button class="btn btn-sm btn-light d-lg-none" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>

            <h1 class="header-title">@yield('title', 'Dashboard')</h1>

            <div class="user-dropdown">
                <div class="user-dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'A' }}
                    </div>
                    <div class="user-name">
                        {{ auth()->check() ? auth()->user()->name : 'Admin' }}
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-store me-2"></i>Visit Store</a></li>
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
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');

            if (window.innerWidth < 992 &&
                !sidebar.contains(event.target) &&
                !toggleBtn.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
