<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" id="sidebar">
    <nav class="sidebar-menu">
        <!-- Dashboard -->
        <a href="index.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </div>
        </a>

        <!-- Management Section -->
        <div class="sidebar-heading">Management</div>
        
        <a href="users.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </div>
        </a>

        <a href="edit_product.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'edit_product.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </div>
        </a>

        <a href="orders.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </div>
        </a>

        <!-- Reports Section -->
        <div class="sidebar-heading">Reports</div>
        
        <a href="analytics.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'analytics.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </div>
        </a>

        <a href="sales.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'sales.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Sales</span>
            </div>
        </a>

        <!-- Settings Section -->
        <div class="sidebar-heading">Settings</div>
        
        <a href="general.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'general.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">General</span>
            </div>
        </a>

        <a href="account.php" class="text-decoration-none">
            <div class="sidebar-item <?php echo $current_page === 'account.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-cog sidebar-icon"></i>
                <span class="sidebar-text">Account</span>
            </div>
        </a>

        <a href="logout.php" class="text-decoration-none">
            <div class="sidebar-item">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-text">Logout</span>
            </div>
        </a>
    </nav>
</aside>
