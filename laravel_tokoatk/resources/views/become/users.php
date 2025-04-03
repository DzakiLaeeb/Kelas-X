<?php
session_start();
require_once '../db_connection.php';

// Get all users from database
try {
    $conn = getConnection();
    $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #5a8ef7;
            --primary-hover: #375de0;
            --danger-color: #dc3545;
            --danger-hover: #bd2130;
            --success-color: #28a745;
            --success-hover: #218838;
            --gray-light: #f8f9fa;
            --gray-border: #e9ecef;
            --text-dark: #212529;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
            --header-height: 60px;
            --transition-speed: 0.3s;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f8f9fa;
            --sidebar-active: #e7f1ff;
            --sidebar-text: #495057;
            --sidebar-active-text: #0d6efd;
            --sidebar-hover-bg: #f8f9fa;
            --sidebar-hover-text: #495057;
            --sidebar-active-bg: rgba(74, 108, 247, 0.1);
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--light-gray);
        }

        .container {
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            color: var(--text-color);
            margin: 0;
        }

        .btn-add {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s;
        }

        .btn-add:hover {
            transform: translateY(-1px);
        }

        .users-table {
            width: 100%;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            background: var(--light-gray);
            padding: 1rem;
            text-align: left;
            color: var(--text-color);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .users-table tr:last-child td {
            border-bottom: none;
        }

        .users-table tr:hover {
            background: var(--primary-light);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-active {
            background: #e6f4ea;
            color: #1e7e34;
        }

        .status-inactive {
            background: #feebed;
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .users-table {
                overflow-x: auto;
            }
        }
        
        /* Default sidebar state */
        .sidebar {
            width: 250px;
            transition: width 0.3s ease;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            padding-top: 60px; /* Adjust based on your header height */
            z-index: 100;
        }

        /* Main content default state */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* Collapsed sidebar state */
        .sidebar.collapsed {
            width: 60px; /* Width enough to show icons */
        }

        /* When sidebar is collapsed, hide text but keep icons */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* When sidebar is collapsed, center icons */
        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }

        /* Expanded main content when sidebar is collapsed */
        .main-content.expanded {
            margin-left: 60px;
        }

        /* Make sidebar toggle button more visible/clickable */
        .navbar-toggle {
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .sidebar.collapsed {
                width: 0;
            }
            
            .sidebar:not(.collapsed) {
                width: 250px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Default sidebar state */
        .sidebar {
            width: 250px;
            transition: width 0.3s ease;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            padding-top: 60px; /* Adjust based on your header height */
            z-index: 100;
        }

        /* Main content default state */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* Collapsed sidebar state */
        .sidebar.collapsed {
            width: 60px; /* Width enough to show icons */
        }

        /* When sidebar is collapsed, hide text but keep icons */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* When sidebar is collapsed, center icons */
        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }

        /* Expanded main content when sidebar is collapsed */
        .main-content.expanded {
            margin-left: 60px;
        }

        /* Make sidebar toggle button more visible/clickable */
        .navbar-toggle {
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.collapsed {
                width: 0;
            }
            
            .sidebar:not(.collapsed) {
                width: 250px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Add this to your CSS */
        .navbar-toggle {
            cursor: pointer;
            z-index: 1000; /* Make sure it's above other elements */
            padding: 10px;
            margin-right: 15px;
            position: relative; /* To ensure z-index works */
            display: block; /* Ensure it's visible */
            width: 40px; /* Give it a specific width */
            height: 40px; /* Give it a specific height */
            text-align: center;
            line-height: 40px;
            background: rgba(0,0,0,0.05); /* Slight background to see where it is */
            border-radius: 4px;
        }

        .navbar-toggle:hover {
            background: rgba(0,0,0,0.1);
        }

        /* Make icon clearly visible */
        .navbar-toggle i {
            font-size: 18px;
        }

        /* Fix any potential parent container issues */
        .header .d-flex {
            position: relative;
            z-index: 1000;
        }

        
        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            height: calc(100vh - var(--header-height));
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: var(--box-shadow);
            overflow-y: auto;
            transition: width var(--transition-speed) ease;
            z-index: 999;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-menu {
            padding: 0; /* Remove the top padding */
            margin-top: 0; /* Remove the top margin */
        }

        /* Add this class for proper spacing between sections */
        .sidebar-heading {
            padding: 12px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--secondary-color);
            letter-spacing: 0.5px;
            margin-top: 10px; /* Add space only above category headings */
        }

        /* Make sure the first sidebar item aligns properly */
        .sidebar-item:first-child {
            margin-top: 0;
            padding-top: 12px;
        }

        .sidebar-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color var(--transition-speed);
        }

        .sidebar-item:hover {
            background-color: #f8f9fa;
        }

        .sidebar-item.active {
            background-color: rgba(74, 108, 247, 0.1);
            border-left: 4px solid var(--primary-color);
            padding-left: 16px;
        }

        .sidebar-item.active .sidebar-icon,
        .sidebar-item.active .sidebar-text {
            color: var(--primary-color);
        }

        .sidebar-icon {
            font-size: 18px;
            width: 24px;
            color: var(--secondary-color);
            margin-right: 15px;
            text-align: center;
        }

        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar-heading {
            padding: 12px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--secondary-color);
            letter-spacing: 0.5px;
        }

        .sidebar.collapsed .sidebar-heading {
            display: none;
        }
        
        /* Update sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar-menu {
            padding: 1rem 0;
            margin-top: -5px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all var(--transition-speed);
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .sidebar-item:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-hover-text);
            border-left-color: var(--gray-border);
        }

        .sidebar-item.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            border-left-color: var(--primary-color);
        }

        .sidebar-item.active .sidebar-icon,
        .sidebar-item.active .sidebar-text {
            color: var(--primary-color);
        }

        .sidebar-icon {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
            transition: all var(--transition-speed);
        }

        .sidebar-text {
            font-size: 0.95rem;
            font-weight: 500;
            white-space: nowrap;
            opacity: 1;
            transition: all var(--transition-speed);
        }

        .sidebar-heading {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Collapsed state */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-heading {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }

        /* Main content adjustments */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed) ease;
            min-height: 100vh;
            padding: 1rem;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.collapsed {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Add this to your existing CSS */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1001;
            padding: 0 1rem;
            display: flex;
            align-items: center;
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .navbar-toggle {
            background: transparent;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--sidebar-text);
            cursor: pointer;
            border-radius: 4px;
            margin-right: 1rem;
            transition: background-color 0.2s;
        }

        .navbar-toggle:hover {
            background-color: var(--sidebar-hover);
        }

        /* Adjust main content padding */
        .main-content {
            padding-top: calc(var(--header-height) + 1rem);
        }
    </style>
</head>
<body>
    <header class="header">
        <button id="navbarToggle" class="navbar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Toko Online</h2>
    </header>

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-menu">
            <a href="index.php" class="sidebar-item">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <div class="sidebar-heading">Management</div>
            <a href="users.php" class="sidebar-item active">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </a>
            <a href="edit_product.php" class="sidebar-item">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </a>
            <a href="orders.php" class="sidebar-item">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </a>
            <a href="banners.php" class="sidebar-item">
                <i class="fas fa-images sidebar-icon"></i>
                <span class="sidebar-text">Banners</span>
            </a>
            <div class="sidebar-heading">Reports</div>
            <a href="analytics.php" class="sidebar-item">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </a>
            <a href="sales.php" class="sidebar-item">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Sales</span>
            </a>
            <div class="sidebar-heading">Settings</div>
            <a href="general.php" class="sidebar-item">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">General</span>
            </a>
            <a href="account.php" class="sidebar-item">
                <i class="fas fa-user-cog sidebar-icon"></i>
                <span class="sidebar-text">Account</span>
            </a>
            <a href="logout.php" class="sidebar-item">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </aside>

    <div class="main-content" id="mainContent">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">User Management</h1>
                <button class="btn-add">
                    <i class="fas fa-plus"></i>
                    Add New User
                </button>
            </div>

            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($users) && !empty($users)): ?>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role'] ?? 'User'); ?></td>
                                    <td>
                                        <?php 
                                            $status = $user['status'] ?? 'active';
                                            $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            $date = new DateTime($user['created_at']);
                                            echo $date->format('d M Y');
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    No users found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && mainContent) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save sidebar state
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarCollapsed');
            
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });
    </script>
</body>
</html>