<?php
session_start(); // Start the session at the beginning of the file  
require_once '../db_connection.php'; // Ensure the database connection is included

// Use the existing connection from db_connection.php
$conn = getConnection(); // This appears to return a PDO connection

$totalUsers = 0;

// Variable to store total orders count
$totalOrders = 0;

// Query to count total orders
try {
    $orderCountQuery = "SELECT COUNT(*) as total FROM orders";
    $orderStmt = $conn->prepare($orderCountQuery);
    $orderStmt->execute();
    $result = $orderStmt->fetch(PDO::FETCH_ASSOC);
if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $orderCountQuery = "SELECT COUNT(*) as total FROM orders WHERE session_id = ?";
    $orderStmt = $conn->prepare($orderCountQuery);
    $orderStmt->bindValue(1, $session_id, PDO::PARAM_STR);
} 
$orderCountQuery = "SELECT COUNT(*) as total FROM orders" . (isset($session_id) ? " WHERE session_id = ?" : "");
$orderStmt = $conn->prepare($orderCountQuery);

$orderStmt->execute();
$result = $orderStmt->fetch(PDO::FETCH_ASSOC);
$totalOrders = $result['total'] ?? 0;


} catch (PDOException $e) {
    // Set a default value if there's an error
    $totalOrders = 0;
    error_log("Order count query error: " . $e->getMessage());
}


// First, handle the user count query using the existing PDO connection
try {
    $userCountQuery = "SELECT COUNT(*) as total FROM users";
    $userStmt = $conn->prepare($userCountQuery);
    $userStmt->execute();
    $result = $userStmt->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $result['total'];
} catch (PDOException $e) {
    // Set a default value if there's an error
    $totalUsers = 0;
    error_log("User count query error: " . $e->getMessage());
}

// Now handle the session orders and improve error handling

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    
    // Retrieve the orders based on the session ID
    echo '<p>Session ID: ' . htmlspecialchars($session_id) . '</p>'; // Display session ID for user reference
    
    try {
        $query = "
            SELECT o.product_id, o.quantity, o.price, p.name 
            FROM orders o
            JOIN products p ON o.product_id = p.id
            WHERE o.session_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $session_id, PDO::PARAM_STR); // Use bindValue for PDO
        
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all orders
        
        if (!empty($orders)) {
            echo '<table>';
            echo '<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';
            foreach ($orders as $order) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($order['name']) . '</td>';
                echo '<td>' . htmlspecialchars($order['quantity']) . '</td>';
                echo '<td>Rp ' . number_format($order['price'], 0, ',', '.') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No orders found for this session. Please check the session ID and try again.</p>';
        }
    } catch (PDOException $e) {
        echo '<p>Error retrieving orders. Please try again later.</p>';
        error_log("Order query error: " . $e->getMessage()); // Log error for debugging
    }
} else {
    echo '<p>No session ID provided.</p>';
}

// Add this at the top of your file after the database connection
try {
    // Get total orders for today
    $todayQuery = "SELECT COUNT(*) as total FROM orders 
                   WHERE DATE(created_at) = CURRENT_DATE()";
    $stmt = $conn->prepare($todayQuery);
    $stmt->execute();
    $todayResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $todayOrders = $todayResult['total'];

    // Get total orders for yesterday
    $yesterdayQuery = "SELECT COUNT(*) as total FROM orders 
                      WHERE DATE(created_at) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
    $stmt = $conn->prepare($yesterdayQuery);
    $stmt->execute();
    $yesterdayResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $yesterdayOrders = $yesterdayResult['total'];

    // Calculate percentage change for orders
    if ($yesterdayOrders > 0) {
        $percentageChange = (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100;
    } else {
        $percentageChange = $todayOrders > 0 ? 100 : 0;
    }

    // Get users registered today
    $todayUsersQuery = "SELECT COUNT(*) as total FROM users 
                        WHERE DATE(created_at) = CURRENT_DATE()";
    $stmt = $conn->prepare($todayUsersQuery);
    $stmt->execute();
    $todayUsersResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $todayUsers = $todayUsersResult['total'];

    // Get users registered yesterday
    $yesterdayUsersQuery = "SELECT COUNT(*) as total FROM users 
                           WHERE DATE(created_at) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
    $stmt = $conn->prepare($yesterdayUsersQuery);
    $stmt->execute();
    $yesterdayUsersResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $yesterdayUsers = $yesterdayUsersResult['total'];

    // Calculate percentage change for users
    if ($yesterdayUsers > 0) {
        $userPercentageChange = (($todayUsers - $yesterdayUsers) / $yesterdayUsers) * 100;
    } else {
        $userPercentageChange = $todayUsers > 0 ? 100 : 0;
    }

} catch (PDOException $e) {
    error_log("Error calculating stats: " . $e->getMessage());
    $percentageChange = 0;
    $userPercentageChange = 0;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== GLOBAL STYLES ===== */
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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fb;
        color: #333;
        line-height: 1.6;
        overflow-x: hidden;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    ul {
        list-style: none;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: background-color var(--transition-speed);
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-secondary {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-success {
        background-color: var(--success-color);
        color: white;
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }
    
    /* Enhanced Card Container Styling */
    .card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 25px;
        overflow: hidden;
        height: auto;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transform: translateY(-4px);
    }

    .card h2, .card h3 {
        padding: 15px 20px;
        margin: 0;
        color: #333;
        font-weight: 600;
        border-bottom: 1px solid #eaeaea;
    }

    .card h3 {
        font-size: 18px;
        background-color: #f8f9fa;
    }

    /* Chart Container */
    .chart-container {
        padding: 0;
        overflow: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Table Styles */
    .table-responsive {
        overflow-y: auto;
        max-height: 450px;
        padding: 0 5px;
        margin-bottom: 10px;
        flex: 1;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table thead {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table thead th {
        background-color: #4a6cf7;
        color: white;
        font-weight: 500;
        text-align: left;
        padding: 12px 15px;
        border: none;
    }

    .table thead th:first-child {
        border-top-left-radius: 6px;
    }

    .table thead th:last-child {
        border-top-right-radius: 6px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #efefef;
    }

    .table tbody tr:hover {
        background-color: rgba(74, 108, 247, 0.05);
        transform: translateX(5px);
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    /* Button Styling */
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        margin-right: 5px;
        display: inline-block;
        text-decoration: none;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Empty State Styling */
    .chart-container p {
        padding: 20px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
    }

    /* Striped Table */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-striped tbody tr:hover {
        background-color: rgba(74, 108, 247, 0.05);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card {
            max-height: 500px;
        }
        
        .table-responsive {
            max-height: 350px;
        }
        
        .btn {
            padding: 4px 8px;
            font-size: 12px;
        }
    }
    /* ===== GRID SYSTEM ===== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col {
        flex-basis: 0;
        flex-grow: 1;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-3 {
        flex: 0 0 25%;
        max-width: 25%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* ===== HEADER STYLES ===== */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: var(--header-height);
        background-color: white;
        box-shadow: var(--box-shadow);
        padding: 0 20px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo img {
        height: 40px;
        margin-right: 10px;
    }

    .logo-text {
        font-weight: bold;
        font-size: 20px;
        color: var(--primary-color);
    }
            font-weight: bold;
            font-size: 20px;
            color: var(--primary-color);
        }

        .navbar-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .notifications {
            position: relative;
            margin-right: 20px;
            cursor: pointer;
        }

        .notifications-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-name {
            font-weight: 600;
        }

        .profile-role {
            font-size: 12px;
            color: var(--secondary-color);
        }

        /* ===== MAIN CONTENT STYLES ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            transition: margin-left var(--transition-speed) ease;
            min-height: calc(100vh - var(--header-height) - var(--footer-height));
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .page-title {
            margin-bottom: 30px;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* ===== DASHBOARD WIDGETS ===== */
        .stats-card {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .stats-icon {
            background-color: rgba(74, 108, 247, 0.1);
            color: var(--primary-color);
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stats-icon.users {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .stats-icon.revenue {
            background-color: rgba(23, 162, 184, 0.1);
            color: var (--info-color);
        }

        .stats-icon.orders {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .stats-title {
            color: var(--secondary-color);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .stats-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stats-comparison {
            font-size: 12px;
        }

        .stats-comparison.positive {
            color: var(--success-color);
        }

        .stats-comparison.negative {
            color: var(--danger-color);
        }

        /* ===== CHART STYLES ===== */
        .chart-container {
            width: 100%;
            height: 300px;
        }

        /* ===== TABLE STYLES ===== */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary-color);
            border-bottom: 1px solid #ddd;
        }

        .table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #efefef;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .table-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .table-actions .edit {
            color: var(--primary-color);
        }

        .table-actions .delete {
            color: var(--danger-color);
        }

        /* ===== FORM STYLES ===== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color var(--transition-speed);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-error {
            color: var(--danger-color);
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        /* ===== MODAL STYLES ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Increased z-index to ensure it's above everything */
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition-speed), visibility var(--transition-speed);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            transition: transform var(--transition-speed), opacity var(--transition-speed);
            opacity: 0;
            z-index: 10000; /* Even higher z-index for the modal itself */
        }

        .modal-overlay.active .modal {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        /* Additional styling for better visibility */
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #efefef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px 8px 0 0; /* Rounded top corners */
        }

        .modal-title {
            font-weight: 600;
            font-size: 18px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: white;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: #f0f0f0;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #efefef;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background-color: #f8f9fa;
            border-radius: 0 0 8px 8px; /* Rounded bottom corners */
        }

        /* Ensure modals are outside any container by appending to body */
        body > .modal-overlay {
            position: fixed;
        }

        /* Prevent page scrolling when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* ===== FOOTER STYLES ===== */
        .footer {
            height: var(--footer-height);
            background-color: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
        }

        .footer.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .footer-text {
            color: var(--secondary-color);
            font-size: 14px;
        }

        .footer-links a {
            margin-left: 15px;
            color: var(--secondary-color);
            font-size: 14px;
            transition: color var(--transition-speed);
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1199.98px) {
            .col-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.mobile-visible {
                transform: translateX(0);
            }

            .main-content, .footer {
                margin-left: 0;
            }

            .main-content.sidebar-collapsed, .footer.sidebar-collapsed {
                margin-left: 0;
            }

            .navbar-toggle {
                display: block;
            }
        }

        @media (max-width: 767.98px) {
            .col-4, .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .profile-info {
                display: none;
            }

            .header {
                padding: 0 15px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .footer {
                padding: 0 15px;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                gap: 10px;
            }

            .footer-links {
                display: flex;
                gap: 15px;
            }

            .footer-links a {
                margin-left: 0;
            }
        }

        /* ===== UTILITY CLASSES ===== */
        .text-primary { color: var(--primary-color); }
        .text-success { color: var(--success-color); }
        .text-warning { color: var(--warning-color); }
        .text-danger { color: var(--danger-color); }
        .text-info { color: var(--info-color); }
        .text-secondary { color: var(--secondary-color); }

        .bg-primary { background-color: var(--primary-color); }
        .bg-success { background-color: var(--success-color); }
        .bg-warning { background-color: var(--warning-color); }
        .bg-danger { background-color: var(--danger-color); }
        .bg-info { background-color: var(--info-color); }
        .bg-secondary { background-color: var(--secondary-color); }

        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }

        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }

        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-center { justify-content: center; }
        .flex-column { flex-direction: column; }
        .flex-wrap { flex-wrap: wrap; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }

        .notification-panel {
            position: absolute;
            right: 0;
            top: 100%;
            width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            z-index: 1001;
            display: none;
        }

        .notification-panel.active {
            display: block;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #efefef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #efefef;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .notification-item.unread {
            background-color: rgba(74, 108, 247, 0.05);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 12px;
            color: var(--secondary-color);
        }

        .search-box {
            position: relative;
            margin-right: 20px;
        }

        .search-input {
            padding: 8px 12px 8px 35px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 200px;
            transition: width var(--transition-speed);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            width: 250px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition-speed), visibility var(--transition-speed);
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .navbar-toggle {
            background: none;
            border: none;
            padding: 10px;
            margin-right: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .navbar-toggle i {
            font-size: 20px;
            color: #333;
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
            border-radius: 4px;
        }

        .navbar-toggle:hover {
            background: rgba(0, 0, 0, 0.14).1);
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

        /* Update chart container styles */
        .chart-container {
            width: 100%;
            min-height: calc(100vh - 250px); /* Subtract header height and padding */
            padding: 0;
            margin: 0;
        }
        /* Card Styles */
        .card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Stats Card Specific Styles */
        .stats-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .stats-title {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stats-value {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        /* Table Styles */
        .table-responsive {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            margin: 24px 0;
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: #f8f9fa;
            padding: 16px;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Grid Layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -12px;
        }

        .col-3, .col-6 {
            padding: 12px;
        }

        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .col-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
            
            .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .col-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .row {
                margin: -8px;
            }
            
            .col-3, .col-6 {
                padding: 8px;
            }
            
            .stats-card {
                padding: 16px;
            }
            
            .table thead th,
            .table tbody td {
                padding: 12px;
            }
        }

        /* Button Styles */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn i {
            font-size: 14px;
        }

        .btn-primary {
            background: #4a6cf7;
            color: white;
        }

        .btn-primary:hover {
            background: #3955d6;
            transform: translateY(-1px);
        }

        /* Header and Section Titles */
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Update main content styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left var(--transition-speed), width var(--transition-speed);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Update chart container styles */
        .chart-container {
            width: 100%;
            min-height: calc(100vh - 250px);
            padding: 0;
            margin: 0;
        }

        /* Update table responsive styles */
        .table-responsive {
            width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Update card styles */
        .card {
            width: 100%;
            margin: 0 0 20px 0;
            border-radius: 8px;
        }

        /* Update row and column styles */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
            width: 100%;
        }

        .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 10px;
        }

        /* Update container styles */
        .container {
            width: 100%;
            max-width: none;
            padding: 0;
            margin: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }

            .row {
                margin: 0 -5px;
            }

            .col-6 {
                padding: 0 5px;
            }
        }

        /* Add to your existing styles */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        /* Make sure images display well on mobile */
        @media (max-width: 768px) {
            .product-thumb {
                width: 40px;
                height: 40px;
            }
        }
        .navbar-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .navbar-toggle:hover {
            background-color: #f8f9fa;
            border-color: #ddd;
        }

        .navbar-toggle i {
            font-size: 1.25rem;
            color: #333;
        }

        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            height: calc(100vh - var(--header-height));
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            z-index: 999;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s ease;
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
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }

        .sidebar-item {
            margin: 0;
            padding: 0;
            /* Remove hover effects from sidebar-item */
        }

        /* Remove any existing hover styles for sidebar-item */
        .sidebar-item:hover {
            background-color: transparent;
        }

        /* Keep hover only on sidebar-link */
        .sidebar-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-hover-text);
            text-decoration: none;
        }

        /* Active state styling */
        .sidebar-item.active .sidebar-link {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Collapsed state */
        .sidebar.collapsed .sidebar-link {
            padding: 12px;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .sidebar-link {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <div class="d-flex align-items-center">
        <button class="navbar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <h2>Admin Dashboard</h2>
        </div>
    </div>
</header>
    <script>
        // Define the toggleSidebar function globally
        function toggleSidebar() {
            console.log("Toggle sidebar function called"); // For debugging
            
            // Get the sidebar and main content elements
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && mainContent) {
                // Toggle the 'collapsed' class on the sidebar
                sidebar.classList.toggle('collapsed');
                
                // Toggle the 'expanded' class on the main content
                mainContent.classList.toggle('expanded');
            } else {
                console.error("Could not find sidebar or mainContent elements");
            }
        }
        
        // Make sure the DOM is fully loaded before adding event listeners
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM loaded");
            
            // Make navbar toggle clickable by adding event listener
            const navbarToggle = document.getElementById('navbarToggle');
            if (navbarToggle) {
                console.log("Found navbar toggle button");
                
                // Add click event listener as a backup to the onclick attribute
                navbarToggle.addEventListener('click', function(event) {
                    console.log("Navbar toggle clicked via event listener");
                    toggleSidebar();
                });
                
                // Make sure the button is visible and clickable
                navbarToggle.style.cursor = 'pointer';
                navbarToggle.style.zIndex = '1000';
                navbarToggle.style.position = 'relative';
            } else {
                console.error("Could not find navbar toggle button");
            }
        });

        // Function to toggle sidebar
        function toggleSidebar() {
            // Get the sidebar and main content elements
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            // Toggle the 'collapsed' class on the sidebar
            sidebar.classList.toggle('collapsed');
            
            // Toggle the 'expanded' class on the main content
            mainContent.classList.toggle('expanded');
        }

        // Add event listener for navbar toggle button
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggle = document.getElementById('navbarToggle');
            if (navbarToggle) {
                navbarToggle.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    // Initialize sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    // Add click event listener
    sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        toggleSidebar();
    });

    // Handle clicks outside sidebar on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) && 
                !sidebar.classList.contains('collapsed')) {
                toggleSidebar();
            }
        }
    });
});
</script>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="d-flex align-items-center">
        <div class="navbar-toggle" id="navbarToggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </div>
            <div class="logo">
                <img src="/api/placeholder/40/40" alt="Admin Logo">
                <div class="logo-text">AdminDash</div>
            </div>
        </div>
        <div class="header-right">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
            <div class="notifications" id="notificationsToggle">
                <i class="fas fa-bell"></i>
                <div class="notifications-count">3</div>
                <!-- Notification Panel -->
                <div class="notification-panel" id="notificationPanel">
                    <div class="notification-header">
                        <h3>Notifications</h3>
                        <a href="#">Mark all as read</a>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notification-icon bg-success text-light">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-text">New user registered</div>
                                <div class="notification-time">2 mins ago</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon bg-primary text-light">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-text">New order received</div>
                                <div class="notification-time">1 hour ago</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon bg-warning text-light">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-text">Server load is high</div>
                                <div class="notification-time">3 hours ago</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon bg-info text-light">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-text">You have a new message</div>
                                <div class="notification-time">Yesterday</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile" id="profileDropdown">
                <img src="/api/placeholder/40/40" alt="Admin Profile" class="profile-img">
                <div class="profile-info">
                    <div class="profile-name">John Doe</div>
                    <div class="profile-role">Administrator</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
    <nav class="sidebar-menu">
        <div class="sidebar-item active">
            <a href="index.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </div>
        
        <div class="sidebar-heading">Management</div>
        <div class="sidebar-item">
            <a href="users.php" class="sidebar-link">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="edit_product.php" class="sidebar-link">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="orders.php" class="sidebar-link">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="banners.php" class="sidebar-link">
            <i class="fas fa-images sidebar-icon"></i>
            <span class="sidebar-text">Banners</span>
            </a>
        </div>
        
        <div class="sidebar-heading">Reports</div>
        <div class="sidebar-item">
            <a href="analytics.php" class="sidebar-link">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="sales.php" class="sidebar-link">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Sales</span>
            </a>
        </div>
        
        <div class="sidebar-heading">Settings</div>
        <div class="sidebar-item">
            <a href="settings.php" class="sidebar-link">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">General</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="account.php" class="sidebar-link">
                <i class="fas fa-user-cog sidebar-icon"></i>
                <span class="sidebar-text">Account</span>
            </a>
        </div>
        <div class="sidebar-item">
            <a href="logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-text">Logout</span>
            </a>
        </div>
    </nav>
</aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <h1 class="page-title">Dashboard</h1>
        
        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-3">
                <div class="card stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-title">Total Revenue</div>
                    <div class="stats-value">$24,350</div>
                    <div class="stats-comparison positive">
                        <i class="fas fa-arrow-up"></i> 12.5% from last month
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card stats-card">
                    <div class="stats-icon users">
                    <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-title">Total Users</div>
                    <div class="stats-value"><?php echo $totalUsers; ?></div>
                    <div class="stats-comparison <?php echo $userPercentageChange >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas fa-arrow-<?php echo $userPercentageChange >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo abs(round($userPercentageChange, 1)); ?>% from yesterday
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card stats-card">
                    <div class="stats-icon revenue">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stats-title">Total Orders</div>
                    <div class="stats-value"><?php echo number_format($totalOrders); ?></div>
                    <div class="stats-comparison <?php echo $percentageChange >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas fa-arrow-<?php echo $percentageChange >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo abs(round($percentageChange, 1)); ?>% from yesterday
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card stats-card">
                    <div class="stats-icon orders">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stats-title">Conversion Rate</div>
                    <div class="stats-value">3.75%</div>
                    <div class="stats-comparison negative">
                        <i class="fas fa-arrow-down"></i> 1.2% from last month
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="card">
                    <h2 class="mb-3">Orders</h2>
                    <div class="chart-container" id="salesChart">
                        <div class="table-responsive">
                            <?php
                            // Start PHP session if not already started
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }
                            
                            // Get session ID from URL or create one if needed
                            if (isset($_GET['session_id'])) {
                                $session_id = $_GET['session_id'];
                            } elseif (isset($_SESSION['session_id'])) {
                                $session_id = $_SESSION['session_id'];
                            } else {
                                // Generate a new session ID and save it
                                $session_id = session_id();
                                $_SESSION['session_id'] = $session_id;
                            }
                            
                            // Now proceed with the query using $session_id
                            if (isset($conn)) {
                                try {
                                    // Base query without session restriction
                                    $query = "
                                        SELECT o.product_id, o.quantity, o.price, o.username, p.name, p.image_url 
                                        FROM orders o
                                        JOIN products p ON o.product_id = p.id
                                    ";
                                    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $checkout_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (!empty($checkout_items)) {
                                        echo '<table class="table table-striped">';
                                        echo '<thead><tr>
                                                <th>Image</th>
                                                <th>Customer</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Actions</th>
                                              </tr></thead>';
                                        echo '<tbody>';
                                        foreach ($checkout_items as $item) {
                                            echo '<tr>';
                                            // Product image column
                                            echo '<td>';
                                            if (!empty($item['image_url'])) {
                                                echo '<img src="' . htmlspecialchars($item['image_url']) . '" 
                                                          alt="' . htmlspecialchars($item['name']) . '" 
                                                          class="product-thumb">';
                                            } else {
                                                echo '<img src="https://via.placeholder.com/50" 
                                                          alt="No image" 
                                                          class="product-thumb">';
                                            }
                                            echo '</td>';
                                            echo '<td>' . htmlspecialchars($item['username']) . '</td>';
                                            echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                                            echo '<td>Rp ' . number_format($item['price'], 0, ',', '.') . '</td>';
                                            echo '<td>
                                                <button class="btn btn-primary edit-btn" 
                                                        onclick="window.location.href=\'orders.php\'" 
                                                        style="background-color: #87CEEB;">
                                                    Preview
                                                </button>
                                            </td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                    } else {
                                        echo '<p>No items checked out for this session.</p>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<p>Error: ' . $e->getMessage() . '</p>';
                                }
                            } else {
                                echo '<p>Database connection error. Please check your connection.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- The modal will be created dynamically and appended to the body -->

                <script>
                    // Function to create and open a modal
                    function openModal(title, content, hasFooter = true) {
                        // Create modal overlay
                        const overlay = document.createElement('div');
                        overlay.className = 'modal-overlay';
                        
                        // Create modal
                        const modal = document.createElement('div');
                        modal.className = 'modal';
                        
                        // Create modal header
                        const header = document.createElement('div');
                        header.className = 'modal-header';
                        
                        const modalTitle = document.createElement('h5');
                        modalTitle.className = 'modal-title';
                        modalTitle.textContent = title;
                        
                        const closeButton = document.createElement('button');
                        closeButton.className = 'modal-close';
                        closeButton.innerHTML = '&times;';
                        closeButton.addEventListener('click', () => closeModal(overlay));
                        
                        header.appendChild(modalTitle);
                        header.appendChild(closeButton);
                        
                        // Create modal body
                        const body = document.createElement('div');
                        body.className = 'modal-body';
                        
                        // If content is a string, set it as innerHTML, otherwise append the element
                        if (typeof content === 'string') {
                            body.innerHTML = content;
                        } else {
                            body.appendChild(content);
                        }
                        
                        // Create modal footer if needed
                        let footer = null;
                        if (hasFooter) {
                            footer = document.createElement('div');
                            footer.className = 'modal-footer';
                            
                            const cancelButton = document.createElement('button');
                            cancelButton.className = 'btn btn-secondary';
                            cancelButton.textContent = 'Cancel';
                            cancelButton.addEventListener('click', () => closeModal(overlay));
                            
                            const confirmButton = document.createElement('button');
                            confirmButton.className = 'btn btn-primary';
                            confirmButton.textContent = 'Confirm';
                            confirmButton.addEventListener('click', () => {
                                // Add your confirm action here
                                closeModal(overlay);
                            });
                            
                            footer.appendChild(cancelButton);
                            footer.appendChild(confirmButton);
                        }
                        
                        // Assemble modal
                        modal.appendChild(header);
                        modal.appendChild(body);
                        if (footer) modal.appendChild(footer);
                        overlay.appendChild(modal);
                        
                        // Append to body to ensure it's outside any container
                        document.body.appendChild(overlay);
                        document.body.classList.add('modal-open');
                        
                        // Force reflow to enable transitions
                        overlay.offsetWidth;
                        
                        // Make overlay visible
                        overlay.classList.add('active');
                        
                        // Add click event to close when clicking outside modal
                        overlay.addEventListener('click', (e) => {
                            if (e.target === overlay) {
                                closeModal(overlay);
                            }
                        });
                        
                        // Add escape key listener
                        document.addEventListener('keydown', function escapeListener(e) {
                            if (e.key === 'Escape') {
                                closeModal(overlay);
                                document.removeEventListener('keydown', escapeListener);
                            }
                        });
                        
                        return overlay;
                    }

                    // Function to close modal
                    function closeModal(overlay) {
                        overlay.classList.remove('active');
                        
                        // Wait for transition to complete before removing
                        setTimeout(() => {
                            document.body.removeChild(overlay);
                            document.body.classList.remove('modal-open');
                        }, 300); // Match this to your transition speed
                    }

                    // Function for edit button
                    function openEditModal(productId) {
                        fetch(`edit_order.php?id=${productId}&modal=1`)
                            .then(response => response.text())
                            .then(html => {
                                const overlay = openModal('Edit Item', html, false);
                                
                                // Get the form inside the modal
                                const form = overlay.querySelector('form');
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        
                                        const formData = new FormData(form);
                                        formData.append('ajax', '1');
                                        
                                        fetch(form.action, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                closeModal(overlay);
                                                location.reload();
                                            }
                                        });
                                    });
                                }
                            });
                    }

                    // Function for delete button
                    function openDeleteModal(productId) {
                        const content = `
                            <p>Are you sure you want to delete this item?</p>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary cancel-delete">Cancel</button>
                                <button type="button" class="btn btn-danger confirm-delete">Delete</button>
                            </div>
                        `;
                        
                        const overlay = openModal('Confirm Deletion', content, false);
                        
                        // Add event listeners to buttons
                        overlay.querySelector('.cancel-delete').addEventListener('click', () => {
                            closeModal(overlay);
                        });
                        
                        overlay.querySelector('.confirm-delete').addEventListener('click', () => {
                            fetch(`delete_order.php?id=${productId}&ajax=1`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        closeModal(overlay);
                                        location.reload();
                                    }
                                });
                        });
                    }

        // Function to execute form submission
        function executeFormSubmit(form, overlay) {
            // Create FormData object from the form
            const formData = new FormData(form);
            formData.append('ajax', '1');
            
            // Show loading state
            const saveBtn = form.querySelector('.save-changes-btn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.textContent = 'Saving...';
            }
            
            // Send the form data
            fetch(form.action || `edit_order.php?id=${form.querySelector('[name="product_id"]')?.value || ''}&ajax=1`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success mt-2';
                    successMsg.textContent = 'Changes saved successfully!';
                    form.insertBefore(successMsg, form.firstChild);
                    
                    // Close modal and reload after brief delay
                    setTimeout(() => {
                        closeModal(overlay);
                        location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to save changes');
                }
            })
            .catch(error => {
                console.error('Error saving changes:', error);
                
                // Show error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'alert alert-danger mt-2';
                errorMsg.textContent = error.message || 'Failed to save changes. Please try again.';
                form.insertBefore(errorMsg, form.firstChild);
                
                // Re-enable the save button
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Changes';
                }
            });
        }

        // Function for delete button
        function openDeleteModal(productId) {
            const content = `
                <p>Are you sure you want to delete this item?</p>
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-secondary mr-2 cancel-delete">Cancel</button>
                    <button type="button" class="btn btn-danger confirm-delete">Delete</button>
                </div>
            `;
            
            const overlay = openModal('Confirm Deletion', content, false);
            
            // Add event listeners to buttons
            overlay.querySelector('.cancel-delete').addEventListener('click', () => {
                closeModal(overlay);
            });
            
            const confirmBtn = overlay.querySelector('.confirm-delete');
            confirmBtn.addEventListener('click', () => {
                // Show loading state
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Deleting...';
                
                // Send delete request
                fetch(`delete_order.php?id=${productId}&ajax=1`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const modalBody = overlay.querySelector('.modal-body');
                            modalBody.innerHTML = '<div class="alert alert-success">Item deleted successfully!</div>';
                            
                            // Close modal and reload after brief delay
                            setTimeout(() => {
                                closeModal(overlay);
                                location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to delete item');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting item:', error);
                        
                        // Show error message
                        const modalBody = overlay.querySelector('.modal-body');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger mt-2';
                        errorDiv.textContent = error.message || 'Failed to delete item. Please try again.';
                        modalBody.appendChild(errorDiv);
                        
                        // Re-enable the confirm button
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Delete';
                    });
            });
        }

        // Add event listeners to buttons
        document.addEventListener('DOMContentLoaded', () => {
            // Edit buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    openEditModal(productId);
                });
            });
            
            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    openDeleteModal(productId);
                });
            });
        });
                </script>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="car