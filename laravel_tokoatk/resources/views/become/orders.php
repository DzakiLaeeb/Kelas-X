<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../db_connection.php';

// Add new functions at the top
function getOrderStatistics($conn) {
    try {
        $stats = [
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'total_revenue' => 0,
            'avg_order_value' => 0
        ];
        
        // Get various order statistics
        $query = "SELECT 
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
            SUM(price * quantity) as total_revenue
            FROM orders";
            
        $result = $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        
        $stats['total_orders'] = $result['total_orders'];
        $stats['pending_orders'] = $result['pending_orders'];
        $stats['completed_orders'] = $result['completed_orders'];
        $stats['total_revenue'] = $result['total_revenue'];
        $stats['avg_order_value'] = $result['total_orders'] > 0 ? 
            $result['total_revenue'] / $result['total_orders'] : 0;
        
        return $stats;
    } catch(PDOException $e) {
        error_log("Error getting order statistics: " . $e->getMessage());
        return $stats;
    }
}

// Get order statistics
$orderStats = getOrderStatistics($conn);

// Get all orders from database
try {
    $conn = getConnection();
    $query = "
        SELECT o.*, p.name as product_name, p.image_url
        FROM orders o
        LEFT JOIN products p ON o.product_id = p.id
        ORDER BY o.created_at DESC
    ";
    
    $stmt = $conn->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | Toko Online</title>
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
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            overflow-x:hidden
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background-color: var(--white);
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 100;
        }

        .header h2 {
            margin-left: 20px;
            color: var(--primary-color);
        }

        .navbar-toggle {
            background: none;
            border: none; 
            color: var(--text-color);
            font-size: 1.2rem;
            cursor: pointer;
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

        /* Table container styles */
        .orders-table {
            width: 100%;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin: 20px 0;
            overflow-x: hidden;
        }

        /* Table styles */
        .orders-table table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Cell styles */
        .orders-table th,
        .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
            word-break: break-word;
        }

        /* Responsive column widths */
        .orders-table th:nth-child(1), 
        .orders-table td:nth-child(1) { width: 5%; } /* ID */

        .orders-table th:nth-child(2), 
        .orders-table td:nth-child(2) { width: 8%; } /* Image */

        .orders-table th:nth-child(3), 
        .orders-table td:nth-child(3) { width: 20%; } /* Product */

        .orders-table th:nth-child(4), 
        .orders-table td:nth-child(4) { width: 7%; } /* Qty */

        .orders-table th:nth-child(5), 
        .orders-table td:nth-child(5) { width: 12%; } /* Price */

        .orders-table th:nth-child(6), 
        .orders-table td:nth-child(6) { width: 12%; } /* Status */

        .orders-table th:nth-child(7), 
        .orders-table td:nth-child(7) { width: 15%; } /* Date */

        .orders-table th:nth-child(8), 
        .orders-table td:nth-child(8) { width: 21%; } /* Actions */

        /* Mobile responsive styles */
        @media screen and (max-width: 1024px) {
            .orders-table table,
            .orders-table thead,
            .orders-table tbody,
            .orders-table tr,
            .orders-table th,
            .orders-table td {
                display: block;
                width: 100%;
            }

            .orders-table tr {
                margin-bottom: 15px;
                border-bottom: 2px solid #edf2f7;
                padding: 10px;
            }

            .orders-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border: none;
            }

            .orders-table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px;
            }

            .orders-table thead {
                display: none;
            }

            .btn-group {
                justify-content: flex-start;
                width: 100%;
                margin-top: 10px;
            }

            .status-badge {
                margin: 5px 0;
            }

            /* Hide table head on mobile */
            .orders-table thead {
                display: none;
            }

            /* Stack content vertically */
            .orders-table td {
                padding: 10px 0;
                text-align: left;
            }

            /* Make product image smaller on mobile */
            .product-thumb {
                width: 40px;
                height: 40px;
            }
        }

        /* Button group styles */
        .btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Status badge styles */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            white-space: nowrap;
        }

        /* Product thumbnail */
        .product-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Table header styles */
        .orders-table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #1a202c;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Table row hover effect */
        .orders-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Product image thumbnail */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Button group in actions column */
        .btn-group {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        /* Responsive adjustments */
        @media (max-width: 1280px) {
            .orders-table {
                margin: 10px -15px;
                border-radius: 0;
            }
        }

        /* Status badge adjustments */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Main content styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 10px;
            padding-top: calc(var(--header-height) + 10px);
            transition: margin-left var(--transition-speed);
            width: calc(100% - var(--sidebar-width));
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .page-title {
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .debug-info {
            background-color: #cce5ff;
            color: #004085;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .btn-view, .btn-edit {
            color: var(--text-color);
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-view:hover, .btn-edit:hover {
            color: var(--primary-color);
        }

        /* Add to your existing CSS in the <style> section */
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-warning {
            background-color:rgb(255, 251, 0); /* Changed to green for "Kemas" */
            color: black;
        }

        .btn-warning:hover {
            background-color:rgb(243, 239, 0);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn i {
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
            }
        }

        /* Update button styles for disabled state */
        .btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
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
        .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-overlay.modal-visible {
        opacity: 1;
    }

    .modal {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        width: 90%;
        max-width: 500px;
        overflow: hidden;
        position: relative; /* Ensure this is positioned relative */
        z-index: 1001; /* Higher than overlay */
        transform: translateY(20px) scale(0.95);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .modal.modal-active {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: rgb(251, 255, 0);
        border-bottom: 1px solid #dee2e6;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        color: black;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: white;
        transition: transform 0.2s ease;
    }
    
    .modal-close:hover {
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 20px;
    }

    .status-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .status-btn {
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background-color: white;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.2s ease;
    }

    .status-btn i {
        font-size: 24px;
        margin-bottom: 8px;
        transition: transform 0.2s ease;
    }

    .status-btn:hover {
        background-color: #f8f9fa;
        border-color: #4a6cf7;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .status-btn:hover i {
        transform: scale(1.2);
    }

    /* Status colors */
    .status-btn[data-status="pending"] i { color: #ffc107; }
    .status-btn[data-status="packing"] i { color: #17a2b8; }
    .status-btn[data-status="shipped"] i { color: #28a745; }
    .status-btn[data-status="review"] i { color: #fd7e14; }
    
    /* Prevent page scrolling when modal is open */
    body.modal-open {
        overflow: hidden;
    }
    
    /* Animation keyframes for toast notifications */
    @keyframes slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fade-out {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-packing {
        background-color: #e2f3ff;
        color: #0c5460;
    }

    .status-shipped {
        background-color: #d4edda;
        color: #155724;
    }

    .status-review {
        background-color: #ffe5d0;
        color: #fd7e14;
    }
    .status-Pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-Sedang-Dikemas {
        background-color: #e2f3ff;
        color: #0c5460;
    }

    .status-Dikirim {
        background-color: #d4edda;
        color: #155724;
    }

    .status-Beri-Penilaian {
        background-color: #ffe5d0;
        color: #fd7e14;
    }

    // Add this to your existing CSS styles

    @keyframes fade-out-row {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }

    .orders-table tr {
        transition: all 0.3s ease;
    }

    /* Table Container */
.orders-table {
    width: 100%;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin: 20px 0;
    overflow-x: hidden;
}

/* Table Base Styles */
.orders-table table {
    width: 100%;
    border-collapse: collapse;
}

/* Desktop Styles (1024px and above) */
@media screen and (min-width: 1024px) {
    .orders-table th,
    .orders-table td {
        padding: 15px;
        text-align: left;
    }

    .orders-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
}

/* Tablet and Mobile Styles (below 1024px) */
@media screen and (max-width: 1023px) {
    .orders-table thead {
        display: none;
    }

    .orders-table,
    .orders-table tbody,
    .orders-table tr,
    .orders-table td {
        display: block;
        width: 100%;
    }

    .orders-table tr {
        margin-bottom: 1rem;
        border: 1px solid #edf2f7;
        border-radius: 8px;
        padding: 1rem;
        background: #fff;
    }

    .orders-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border: none;
    }

    .orders-table td::before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: 1rem;
        min-width: 120px;
    }

    /* Special handling for image and actions columns */
    .orders-table td[data-label="Image"],
    .orders-table td[data-label="Actions"] {
        flex-direction: column;
        align-items: flex-start;
    }

    .orders-table td[data-label="Image"]::before {
        margin-bottom: 0.5rem;
    }

    .orders-table td[data-label="Actions"] .btn-group {
        width: 100%;
        justify-content: flex-start;
        margin-top: 0.5rem;
    }

    .product-thumb {
        width: 60px;
        height: 60px;
    }
}

/* Small Tablets (below 768px) */
@media screen and (max-width: 767px) {
    .orders-table td::before {
        min-width: 100px;
        font-size: 0.9rem;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .product-thumb {
        width: 50px;
        height: 50px;
    }
}

/* Mobile Phones (below 480px) */
@media screen and (max-width: 479px) {
    .orders-table tr {
        padding: 0.75rem;
    }

    .orders-table td {
        font-size: 0.9rem;
        padding: 0.4rem 0;
    }

    .orders-table td::before {
        min-width: 90px;
        font-size: 0.85rem;
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 4px 8px;
    }

    .btn {
        font-size: 0.85rem;
        padding: 6px 12px;
    }

    .product-thumb {
        width: 40px;
        height: 40px;
    }
}

/* Status Badge Styles */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
    display: inline-block;
    text-align: center;
    white-space: nowrap;
}

/* Button Group Styles */
.btn-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

/* Container Padding */
.container {
    padding: 15px;
    max-width: 100%;
}

@media screen and (min-width: 1280px) {
    .container {
        padding: 30px;
    }
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(4px);
}

.modal {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 500px;
    overflow: hidden;
    position: relative;
    z-index: 1001;
    transform: translateY(20px) scale(0.95);
    opacity: 0;
    transition: all 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: linear-gradient(135deg, #FFD700, #FFC107);
    border-bottom: none;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #000;
    font-weight: 600;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.modal-close {
    background: rgba(0, 0, 0, 0.1);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #000;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: rgba(0, 0, 0, 0.2);
    transform: rotate(90deg);
}

.modal-body {
    padding: 25px;
}

.status-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    padding: 10px;
}

.status-btn {
    padding: 20px;
    border: 2px solid #FFE082;
    border-radius: 12px;
    background-color: #FFFDE7;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.3s ease;
}

.status-btn i {
    font-size: 28px;
    margin-bottom: 12px;
    transition: transform 0.3s ease;
    color: #FFC107;
}

.status-btn:hover {
    background-color: #FFF8E1;
    border-color: #FFB300;
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(255, 193, 7, 0.2);
}

.status-btn:hover i {
    transform: scale(1.2);
}

/* Status specific colors */
.status-btn[data-status="pending"] {
    border-color: #FFE082;
}

.status-btn[data-status="packing"] {
    border-color: #FFD54F;
}

.status-btn[data-status="shipped"] {
    border-color: #FFB300;
}

.status-btn[data-status="review"] {
    border-color: #FFA000;
}

/* Animation improvements */
.modal-overlay.modal-visible {
    opacity: 1;
}

.modal.modal-active {
    transform: translateY(0) scale(1);
    opacity: 1;
}

/* Mobile responsiveness */
@media screen and (max-width: 480px) {
    .modal {
        width: 95%;
        margin: 10px;
    }

    .status-options {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .modal-header {
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .status-btn {
        padding: 15px;
    }
}
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: rgba(74, 108, 247, 0.1);
        color: var(--primary);
    }

    .stat-icon.pending {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .stat-icon.completed {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .stat-icon.revenue {
        background: rgba(102, 16, 242, 0.1);
        color: #6610f2;
    }

    .stat-info h3 {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0 0 0.5rem 0;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .order-filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-box input {
        width: 90%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-group select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        background: white;
        min-width: 150px;
    }

    @media (max-width: 768px) {
        .order-filters {
            flex-direction: column;
        }
        
        .search-box {
            width: 100%;
        }
        
        .filter-group {
            width: 100%;
            justify-content: stretch;
        }
        
        .filter-group select {
            flex: 1;
        }
    }
</style>
</head>
<body>
<header class="header">
    <div class="d-flex">
        <button class="navbar-toggle" id="navbarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Toko Online</h2>
    </div>
</header>

    <div class="toast-container" id="toastContainer"></div>

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-menu">
            <a href="index.php" class="sidebar-item">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <div class="sidebar-heading">Management</div>
            <a href="users.php" class="sidebar-item">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </a>
            <a href="edit_product.php" class="sidebar-item">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </a>
            <a href="orders.php" class="sidebar-item active">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
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

    <!-- Main content -->
    <div class="main-content" id="mainContent">
        <div class="container">
            <h1 class="page-title">Order Management</h1>

            <!-- Add this after the page title and before the orders table -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p class="stat-value"><?php echo number_format($orderStats['total_orders']); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending Orders</h3>
                        <p class="stat-value"><?php echo number_format($orderStats['pending_orders']); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Completed Orders</h3>
                        <p class="stat-value"><?php echo number_format($orderStats['completed_orders']); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p class="stat-value">Rp <?php echo number_format($orderStats['total_revenue'], 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Add order filters -->
            <div class="order-filters">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="orderSearch" placeholder="Search orders...">
                </div>
                <div class="filter-group">
                    <select id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="packing">Packing</option>
                        <option value="shipped">Shipped</option>
                        <option value="completed">Completed</option>
                    </select>
                    <select id="dateFilter">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                    <button id="exportBtn" class="btn btn-primary">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>

            <?php if(isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Debug info (uncomment untuk debugging) -->
            <?php if(isset($userIdColumn)): ?>
            <div class="debug-info">
                <p><strong>Field ID user:</strong> <?php echo $userIdColumn ?? 'Tidak ditemukan'; ?></p>
            </div>
            <?php endif; ?>

            <div class="orders-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($orders) && !empty($orders)): ?>
                            <?php foreach($orders as $order): ?>
                                <tr data-id="<?php echo htmlspecialchars($order['id']); ?>">
                                    <td data-label="ID">#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td data-label="Image">
                                        <?php if(!empty($order['image_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($order['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($order['product_name']); ?>"
                                                 class="product-thumb"
                                                 loading="lazy">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/50" 
                                                 alt="No image" 
                                                 class="product-thumb">
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Product">
                                        <?php 
                                            if(!empty($order['product_name'])) {
                                                echo htmlspecialchars($order['product_name']);
                                            } else {
                                                echo "Product #" . htmlspecialchars($order['product_id'] ?? 'Unknown');
                                            }
                                        ?>
                                    </td>
                                    <td data-label="Quantity">
                                        <?php echo htmlspecialchars($order['quantity'] ?? '0'); ?>
                                    </td>
                                    <td data-label="Price">
                                        Rp <?php echo number_format(($order['price'] ?? 0) * ($order['quantity'] ?? 0), 0, ',', '.'); ?>
                                    </td>
                                    <td data-label="Status">
                                        <span class="status-badge status-<?php echo $order['status'] ?? 'pending'; ?>">
                                            <?php echo ucfirst($order['status'] ?? 'pending'); ?>
                                        </span>
                                    </td>
                                    <td data-label="Date">
                                        <?php 
                                            echo !empty($order['created_at']) 
                                                ? (new DateTime($order['created_at']))->format('d M Y H:i')
                                                : 'N/A';
                                        ?>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="btn-group">
                                            <button class="btn btn-warning edit-btn" data-id="<?php echo htmlspecialchars($order['id']); ?>">
                                                <i class="fas fa-box"></i> Kemas
                                            </button>
                                            <button class="btn btn-danger delete-btn" data-id="<?php echo htmlspecialchars($order['id']); ?>">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            <!-- Modal HTML Structure -->
                                            <div class="modal-overlay" id="statusModal">
                                                <div class="modal">
                                                    <div class="modal-header">
                                                        <h3>Update Order Status</h3>
                                                        <button class="modal-close" id="modalCloseBtn">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="status-options">
                                                            <button class="status-btn" data-status="pending">
                                                                <i class="fas fa-clock"></i> Pending
                                                            </button>
                                                            <button class="status-btn" data-status="packing">
                                                                <i class="fas fa-box"></i> Sedang Dikemas
                                                            </button>
                                                            <button class="status-btn" data-status="shipped">
                                                                <i class="fas fa-shipping-fast"></i> Dikirim
                                                            </button>
                                                            <button class="status-btn" data-status="review">
                                                                <i class="fas fa-star"></i> Beri Penilaian
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>                                                
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem;">
                                    No orders found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Toast notification styles
    const toastStyles = `
        <style>
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1060;
            }
            .toast {
                background: white;
                border-radius: 4px;
                padding: 1rem;
                margin-bottom: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-width: 200px;
                animation: slide-in 0.3s ease-out;
            }
            .toast.success {
                border-left: 4px solid #28a745;
            }
            .toast.error {
                border-left: 4px solid #dc3545;
            }
            .toast-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0 0.5rem;
            }
        </style>
    `;
    document.head.insertAdjacentHTML('beforeend', toastStyles);

    // Toast notification function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-message">${message}</div>
            <button class="toast-close">&times;</button>
        `;
        container.appendChild(toast);
        
        // Add event listener to toast close button
        toast.querySelector('.toast-close').addEventListener('click', function() {
            toast.style.animation = 'fade-out 0.3s ease-out forwards';
            setTimeout(() => toast.remove(), 300);
        });
        
        setTimeout(() => {
            toast.style.animation = 'fade-out 0.3s ease-out forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Show modal function with animation
    function showModal(orderId) {
        const modal = document.getElementById('statusModal');
        const modalContent = modal.querySelector('.modal');
        
        modal.dataset.orderId = orderId;
        modal.style.display = 'flex';
        modal.classList.add('modal-visible');
        
        // Animate the modal entrance
        setTimeout(() => {
            modalContent.classList.add('modal-active');
        }, 10); // Small delay to ensure transition works
        
        // Prevent body scrolling when modal is open
        document.body.classList.add('modal-open');
    }

    // Hide modal function with animation
    function hideModal() {
        const modal = document.getElementById('statusModal');
        const modalContent = modal.querySelector('.modal');
        
        // First fade out the modal content
        modalContent.classList.remove('modal-active');
        
        // Then fade out the background and hide the modal
        setTimeout(() => {
            modal.classList.remove('modal-visible');
            setTimeout(() => {
                modal.style.display = 'none';
                // Re-enable body scrolling
                document.body.classList.remove('modal-open');
            }, 300); // Match this with the CSS transition duration
        }, 200); // Small delay to make the animation sequence smooth
    }

    // Update status function with improved error handling
    function updateStatus(orderId, status, row) {
        const formData = new FormData();
        formData.append('order_id', orderId);
        formData.append('status', status);

        fetch('update_order_status.php', {
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
                // Update the status badge in the table
                const statusBadge = row.querySelector('.status-badge');
                statusBadge.className = `status-badge status-${status}`;
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                
                // Show success message
                showToast('Status berhasil diperbarui', 'success');
                
                // Close the modal
                hideModal();
            } else {
                throw new Error(data.message || 'Gagal memperbarui status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Error memperbarui status', 'error');
        });
    }

    // Edit button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            const orderId = this.getAttribute('data-id');
            showModal(orderId);
        });
    });

    // Status button event listeners
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            const modal = document.getElementById('statusModal');
            const orderId = modal.dataset.orderId;
            const status = this.dataset.status;
            const row = document.querySelector(`tr[data-id="${orderId}"]`);
            
            if (row && orderId && status) {
                updateStatus(orderId, status, row);
            } else {
                showToast('Data order tidak valid', 'error');
            }
        });
    });

    // Close modal button
    const closeBtn = document.getElementById('modalCloseBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            hideModal();
        });
    }

    // Close modal when clicking outside but NOT when clicking inside
    const statusModal = document.getElementById('statusModal');
    if (statusModal) {
        statusModal.addEventListener('click', function(e) {
            if (e.target === this) { // Only if clicking the overlay itself
                hideModal();
            }
        });
    }
    
    // Prevent modal inner clicks from closing the modal
    const modalInner = document.querySelector('#statusModal .modal');
    if (modalInner) {
        modalInner.addEventListener('click', function(e) {
            e.stopPropagation(); // Stop click from reaching the overlay
        });
    }
    
    // Add "Escape" key support to close the modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('statusModal').style.display === 'flex') {
            hideModal();
        }
    });

    // Add this inside your DOMContentLoaded event listener

    // Cancel order function
    function cancelOrder(orderId, row) {
        if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Pesanan akan dihapus permanen.')) {
            fetch('cancel_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    order_id: orderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    row.style.animation = 'fade-out 0.3s ease-out forwards';
                    setTimeout(() => {
                        row.remove();
                        
                        // Check if table is empty and show message if needed
                        const tbody = document.querySelector('.orders-table tbody');
                        if (tbody.children.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem;">
                                        No orders found
                                    </td>
                                </tr>
                            `;
                        }
                    }, 300);
                    
                    // Show success message
                    showToast('Pesanan berhasil dibatalkan dan dihapus', 'success');
                } else {
                    throw new Error(data.message || 'Gagal membatalkan pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error membatalkan pesanan: ' + error.message, 'error');
            });
        }
    }

    // Add event listeners for cancel buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-id');
            const row = this.closest('tr');
            cancelOrder(orderId, row);
        });
    });
});

// Add this after your existing scripts
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    if (sidebar && mainContent) {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        // Store the state in localStorage
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
}

// Check saved state on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const savedState = localStorage.getItem('sidebarCollapsed');
    
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }
    
    // Add click event to navbar toggle button
    const navbarToggle = document.getElementById('navbarToggle');
    if (navbarToggle) {
        navbarToggle.addEventListener('click', toggleSidebar);
    }
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('sidebar');
            const navbarToggle = document.getElementById('navbarToggle');
            
            if (!sidebar.contains(e.target) && !navbarToggle.contains(e.target)) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const orderSearch = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const exportBtn = document.getElementById('exportBtn');
    const orderRows = document.querySelectorAll('.orders-table tbody tr');

    // Search functionality
    orderSearch.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
    dateFilter.addEventListener('change', filterOrders);

    function filterOrders() {
        const searchTerm = orderSearch.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        orderRows.forEach(row => {
            const orderText = row.textContent.toLowerCase();
            const orderStatus = row.querySelector('.status-badge').textContent.toLowerCase();
            const orderDate = new Date(row.querySelector('[data-label="Date"]').textContent);
            
            let dateMatch = true;
            if (dateValue) {
                const today = new Date();
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

                switch(dateValue) {
                    case 'today':
                        dateMatch = orderDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        dateMatch = orderDate >= weekAgo;
                        break;
                    case 'month':
                        dateMatch = orderDate >= monthAgo;
                        break;
                }
            }

            const matches = orderText.includes(searchTerm) && 
                           (!statusValue || orderStatus.includes(statusValue)) &&
                           dateMatch;

            row.style.display = matches ? '' : 'none';
        });
    }

    // Export functionality
    exportBtn.addEventListener('click', function() {
        const visibleRows = Array.from(orderRows).filter(row => row.style.display !== 'none');
        const csvContent = [
            ['Order ID', 'Product', 'Quantity', 'Price', 'Status', 'Date'],
            ...visibleRows.map(row => [
                row.querySelector('[data-label="ID"]').textContent,
                row.querySelector('[data-label="Product"]').textContent,
                row.querySelector('[data-label="Quantity"]').textContent,
                row.querySelector('[data-label="Price"]').textContent,
                row.querySelector('.status-badge').textContent,
                row.querySelector('[data-label="Date"]').textContent
            ])
        ];

        const csv = csvContent.map(row => row.join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', `orders-${new Date().toISOString().split('T')[0]}.csv`);
        a.click();
    });
});
</script>

</body>
</html>