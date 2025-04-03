
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ayam Goreng Joss</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #e63946;
            --primary-yellow: #ffb703;
            --secondary-yellow: #ffd166;
            --dark-brown: #5c3c10;
            --light-cream: #fff1e6;
            --admin-light: #f8f9fa;
            --admin-border: #e9ecef;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-cream);
        }
        
        .navbar {
            background: var(--primary-red) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-yellow) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
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

        .admin-container {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        
        .admin-heading {
            color: var(--primary-red);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .admin-heading:after {
            content: "";
            position: absolute;
            width: 60%;
            height: 3px;
            background-color: var(--primary-yellow);
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }
        
        .admin-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .admin-card .card-header {
            background-color: var(--primary-red);
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
        }
        
        .btn-admin-primary {
            background-color: var(--primary-red);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-admin-primary:hover {
            background-color: #d52b38;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-admin-secondary {
            background-color: var(--primary-yellow);
            color: var(--dark-brown);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-admin-secondary:hover {
            background-color: var(--secondary-yellow);
            color: var(--dark-brown);
            transform: translateY(-2px);
        }
        
        .table-admin {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .table-admin thead th {
            background-color: var(--primary-red);
            color: white;
            padding: 15px;
            font-weight: 600;
        }
        
        .table-admin tbody tr:nth-child(even) {
            background-color: rgba(255, 183, 3, 0.1);
        }
        
        .table-admin tbody tr:hover {
            background-color: rgba(255, 183, 3, 0.2);
        }
        
        .table-admin td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .badge-admin {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .badge-admin-primary {
            background-color: var(--primary-red);
            color: white;
        }
        
        .badge-admin-secondary {
            background-color: var(--primary-yellow);
            color: var(--dark-brown);
        }
        
        .admin-sidebar {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .admin-sidebar-item {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .admin-sidebar-item:hover {
            background-color: rgba(255, 183, 3, 0.1);
        }
        
        .admin-sidebar-item.active {
            background-color: var(--primary-red);
            color: white;
        }
        
        .admin-sidebar-item i {
            margin-right: 10px;
            color: var(--primary-red);
        }
        
        .admin-sidebar-item.active i {
            color: var(--primary-yellow);
        }

        .admin-dropdown-btn {
            background-color: var(--primary-red);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .admin-dropdown-btn:hover, 
        .admin-dropdown-btn:focus {
            background-color: #d52b38;
            color: white;
            transform: translateY(-2px);
        }

        .admin-dropdown-menu {
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
        }

        .admin-dropdown-menu .dropdown-header {
            color: var(--primary-red);
            font-weight: 600;
            padding: 8px 20px;
        }

        .admin-dropdown-menu .dropdown-divider {
            margin: 8px 0;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .admin-dropdown-menu .dropdown-item {
            padding: 8px 20px;
            color: var(--dark-brown);
            transition: all 0.3s ease;
        }

        .admin-dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 183, 3, 0.1);
            color: var(--primary-red);
            transform: translateX(5px);
        }

        .admin-dropdown-menu .dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--primary-red);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.menu.index') }}">
                <i class="fas fa-drumstick-bite"></i> Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                            <i class="fas fa-utensils me-1"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                            <i class="fas fa-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.order.*') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}" href="{{ route('admin.pelanggan.index') }}">
                            <i class="fas fa-users me-1"></i> Pelanggan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                            <i class="fas fa-chart-bar me-1"></i> Laporan
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    <div class="dropdown">
                        <button class="btn admin-dropdown-btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i> Admin Panel
                        </button>
                        <ul class="dropdown-menu admin-dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-header">Administrator</li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Pengaturan Sistem
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-cog me-2"></i> Profil Admin
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-bell me-2"></i> Notifikasi
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="py-4 mt-4 border-top" style="background-color: var(--dark-brown); color: white;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Ayam Goreng Joss Admin Panel</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>














