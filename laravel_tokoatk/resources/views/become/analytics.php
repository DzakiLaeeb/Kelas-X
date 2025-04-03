<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
require_once '../db_connection.php';

// Fetch analytics data
function getAnalyticsData($conn) {
    try {
        if (!$conn) {
            throw new PDOException("Database connection not established");
        }

        $data = [
            'revenue' => 0,
            'orders' => 0,
            'products' => 0,
            'monthlyData' => [],
            'topProducts' => []
        ];

        // Total revenue
        $revenueQuery = "SELECT COALESCE(SUM(total_amount), 0) as total_revenue FROM orders WHERE status = 'completed'";
        $revenueStmt = $conn->query($revenueQuery);
        $data['revenue'] = $revenueStmt ? $revenueStmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] : 0;

        // Total orders
        $ordersQuery = "SELECT COUNT(*) as total_orders FROM orders";
        $ordersStmt = $conn->query($ordersQuery);
        $data['orders'] = $ordersStmt ? $ordersStmt->fetch(PDO::FETCH_ASSOC)['total_orders'] : 0;

        // Total products
        $productsQuery = "SELECT COUNT(*) as total_products FROM products";
        $productsStmt = $conn->query($productsQuery);
        $data['products'] = $productsStmt ? $productsStmt->fetch(PDO::FETCH_ASSOC)['total_products'] : 0;

        // Monthly sales data
        $monthlyQuery = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COALESCE(SUM(total_amount), 0) as revenue,
            COUNT(*) as orders
            FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month ASC";
        $monthlyStmt = $conn->query($monthlyQuery);
        $data['monthlyData'] = $monthlyStmt ? $monthlyStmt->fetchAll(PDO::FETCH_ASSOC) : [];

        // Top selling products
        $topProductsQuery = "SELECT 
            p.name,
            COUNT(oi.product_id) as total_sold
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            GROUP BY oi.product_id, p.name
            ORDER BY total_sold DESC
            LIMIT 5";
        $topProductsStmt = $conn->query($topProductsQuery);
        $data['topProducts'] = $topProductsStmt ? $topProductsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

        return $data;

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return [
            'revenue' => 0,
            'orders' => 0,
            'products' => 0,
            'monthlyData' => [],
            'topProducts' => []
        ];
    }
}

$analyticsData = getAnalyticsData($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Toko Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Base styles from your existing template */
        :root {
            --primary-color: #5a8ef7;
            --primary-hover: #375de0;
            --text-dark: #212529;
            --gray-light: #f8f9fa;
        }

        /* Analytics specific styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .top-products {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .top-products-list {
            list-style: none;
            padding: 0;
        }

        .top-products-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-light);
        }

        .top-products-item:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Sidebar and Layout Styles */
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
            --sidebar-text: #ecf0f1;
            --transition-speed: 0.3s;
        }

        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            height: calc(100vh - var(--header-height));
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-heading {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(236, 240, 241, 0.6);
        }

        .sidebar-item {
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }

        .sidebar-item.active {
            background: var(--sidebar-active);
            border-left-color: #fff;
        }

        .sidebar-icon {
            width: 20px;
            text-align: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-heading {
            display: none;
        }

        .sidebar.collapsed .sidebar-item {
            padding: 0.8rem;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--header-height) + 20px) 20px 20px;
            transition: margin var(--transition-speed) ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

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

            .sidebar-text,
            .sidebar-heading {
                display: block !important;
            }
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--sidebar-hover);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--sidebar-active);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <!-- Replace the sidebar include with direct sidebar code -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-menu">
            <a href="index.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </div></a>
            <div class="sidebar-heading">Management</div>
            <a href="users.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </div></a>
            <a href="edit_product.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </div></a>
            <a href="orders.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </div></a>
            <div class="sidebar-heading">Reports</div>
            <a href="analytics.php" style="text-decoration: none;"><div class="sidebar-item active">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </div></a
            <a href="sales.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Sales</span>
            </div></a>
            <div class="sidebar-heading">Settings</div>
            <a href="general.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">General</span>
            </div></a>
            <a href="account.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-user-cog sidebar-icon"></i>
                <span class="sidebar-text">Account</span>
            </div></a>
            <a href="logout.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-text">Logout</span>
            </div></a>
        </nav>
    </aside>

    <div class="main-content" id="mainContent">
        <div class="container">
            <h1>Analytics Dashboard</h1>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="value">Rp <?php echo number_format($analyticsData['revenue'] ?? 0, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="value"><?php echo number_format($analyticsData['orders'] ?? 0); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="value"><?php echo number_format($analyticsData['products'] ?? 0); ?></div>
                </div>
            </div>

            <!-- Charts -->
            <div class="charts-grid">
                <!-- Revenue Chart -->
                <div class="chart-container">
                    <h3>Monthly Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>

                <!-- Orders Chart -->
                <div class="chart-container">
                    <h3>Monthly Orders</h3>
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="top-products">
                <h3>Top Selling Products</h3>
                <?php if (!empty($analyticsData['topProducts'])): ?>
                    <ul class="top-products-list">
                        <?php foreach ($analyticsData['topProducts'] as $product): ?>
                            <li class="top-products-item">
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                                <span><?php echo number_format($product['total_sold']); ?> sold</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No product sales data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const monthlyData = <?php echo json_encode($analyticsData['monthlyData']); ?>;
        const months = monthlyData.map(item => item.month);
        const revenue = monthlyData.map(item => item.revenue);
        const orders = monthlyData.map(item => item.orders);

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: revenue,
                    borderColor: '#5a8ef7',
                    backgroundColor: '#5a8ef7',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Orders Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Orders',
                    data: orders,
                    backgroundColor: '#5a8ef7'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>