<?php
session_start();
require_once '../db_connection.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            addProduct();
        } elseif ($_POST['action'] === 'edit') {
            editProduct();
        } elseif ($_POST['action'] === 'delete') {
            deleteProduct();
        }
    }
}

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Add a new product to the database
 */
function addProduct() {
    global $conn;
    
    try {
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['price'])) {
            $_SESSION['error'] = "Nama produk dan harga harus diisi!";
            return;
        }
        
        // Process image
        $imageUrl = "";
        if ($_POST['image_source'] === 'upload' && isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
            // Handle file upload
            $targetDir = "uploads/products/";
            
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES["image_file"]["name"]);
            $targetPath = $targetDir . $fileName;
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image_file']['type'], $allowedTypes)) {
                $_SESSION['error'] = "Hanya file JPG, PNG, GIF, dan WEBP yang diperbolehkan.";
                return;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetPath)) {
                $imageUrl = $targetPath;
            } else {
                $_SESSION['error'] = "Gagal mengupload gambar.";
                return;
            }
        } elseif ($_POST['image_source'] === 'url' && !empty($_POST['image_url'])) {
            // Use provided URL
            $imageUrl = $_POST['image_url'];
        }
        
        // Prepare and execute insert statement
        $stmt = $conn->prepare("
            INSERT INTO products (name, price, description, image_url, created_at) 
            VALUES (:name, :price, :description, :image_url, NOW())
        ");
        
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':image_url', $imageUrl);
        
        $stmt->execute();
        
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

/**
 * Edit an existing product
 */
function editProduct() {
    global $conn;
    
    try {
        // Validate required fields 
        if (empty($_POST['product_id']) || empty($_POST['name']) || empty($_POST['price']) || !isset($_POST['stock'])) {
            $_SESSION['error'] = "ID produk, nama, harga, dan stok harus diisi!";
            return;
        }

        // Check if product exists
        $checkStmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $checkStmt->bindParam(':id', $_POST['product_id']);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            return;
        }
        
        $currentProduct = $checkStmt->fetch(PDO::FETCH_ASSOC);
        $imageUrl = $currentProduct['image_url']; // Default to current image
        
        // Process image if changed
        if ($_POST['image_source'] === 'upload' && isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
            // Handle file upload
            $targetDir = "uploads/products/";
            
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES["image_file"]["name"]);
            $targetPath = $targetDir . $fileName;
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image_file']['type'], $allowedTypes)) {
                $_SESSION['error'] = "Hanya file JPG, PNG, GIF, dan WEBP yang diperbolehkan.";
                return;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetPath)) {
                $imageUrl = $targetPath;
                
                // Delete old image if it's a local file
                if (!empty($currentProduct['image_url']) && strpos($currentProduct['image_url'], 'http') !== 0 && file_exists($currentProduct['image_url'])) {
                    unlink($currentProduct['image_url']);
                }
            } else {
                $_SESSION['error'] = "Gagal mengupload gambar.";
                return;
            }
        } elseif ($_POST['image_source'] === 'url' && !empty($_POST['image_url'])) {
            // Use provided URL
            $imageUrl = $_POST['image_url'];
            
            // Delete old image if it's a local file
            if (!empty($currentProduct['image_url']) && strpos($currentProduct['image_url'], 'http') !== 0 && file_exists($currentProduct['image_url'])) {
                unlink($currentProduct['image_url']);
            }
        }
        
        // Prepare and execute update statement
        $stmt = $conn->prepare("
            UPDATE products 
            SET name = :name, 
                price = :price, 
                stock = :stock,
                description = :description, 
                image_url = :image_url,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        $stmt->bindParam(':id', $_POST['product_id']);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':price', $_POST['price']);
        $stmt->bindParam(':stock', $_POST['stock'], PDO::PARAM_INT); // Explicitly bind as integer
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':image_url', $imageUrl);
        
        $stmt->execute();
        
        $_SESSION['success'] = "Produk berhasil diperbarui!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

/**
 * Delete a product
 */
function deleteProduct() {
    global $conn;
    
    try {
        // Validate product ID
        if (empty($_POST['product_id'])) {
            $_SESSION['error'] = "ID produk tidak valid!";
            return;
        }
        
        // Check if product exists and get image path
        $checkStmt = $conn->prepare("SELECT image_url FROM products WHERE id = :id");
        $checkStmt->bindParam(':id', $_POST['product_id']);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            return;
        }
        
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete product from database
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $_POST['product_id']);
        $stmt->execute();
        
        // Delete product image if it's a local file
        if (!empty($product['image_url']) && strpos($product['image_url'], 'http') !== 0 && file_exists($product['image_url'])) {
            unlink($product['image_url']);
        }
        
        $_SESSION['success'] = "Produk berhasil dihapus!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

/**
 * Get product by ID (for AJAX calls)
 */
function getProductById($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

// Handle AJAX requests for product data
if (isset($_GET['action']) && $_GET['action'] === 'get_product' && isset($_GET['id'])) {
    $product = getProductById($_GET['id']);
    
    if ($product) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Toko Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- CSS remains the same as your original code -->
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        /* Update container styles */
        .container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            background: #ffffff;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        
        h1 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        /* Update action-bar for better spacing */
        .action-bar {
            padding: 0px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        /* Update search box for better responsiveness */
        .search-box {
            flex: 1;
            min-width: 200px;
            max-width: 400px;
        }
        
        .search-box {
            position: relative;
            max-width: 300px;
            width: 100%;
        }
        
        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 142, 247, 0.2);
            outline: none;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: var(--success-hover);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: var(--danger-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        
        /* Update table responsive styles */
        .table-responsive {
            margin-top: 20px;
            width: 100%;
            overflow-x: auto;
        }
        
        .products-table {
            width: 100%;
            min-width: 800px; /* Minimum width to prevent squishing */
        }
        
        .products-table th,
        .products-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .products-table th {
            background-color: var(--gray-light);
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
        }
        
        .products-table tr:hover {
            background-color: rgba(90, 142, 247, 0.05);
        }
        
        .products-table .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            background-color: #f9f9f9;
            display: block;
            border: 1px solid #eee;
        }
        
        .products-table .product-name {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .products-table .product-price {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .edit-btn {
            background-color: #e7f1ff;
            color: var(--primary-color);
        }
        
        .edit-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .delete-btn {
            background-color: #fde8e8;
            color: var(--danger-color);
        }
        
        .delete-btn:hover {
            background-color: var(--danger-color);
            color: white;
        }
        
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin: 40px 0;
        }
        
        .empty-state i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #666;
        }
        
        .empty-state p {
            color: #888;
            margin-bottom: 25px;
        }
        
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin: 40px 0;
        }
        
        .empty-state i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #666;
        }
        
        .empty-state p {
            color: #888;
            margin-bottom: 25px;
        }
        
        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-border);
            background-color: var(--gray-light);
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            margin-left: 1rem;
            line-height: 1;
        }

        .modal-close:hover {
            color: var(--text-dark);
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 16px 20px;
            border-top: 1px solid var(--gray-border);
            gap: 10px;
        }

        /* Form styles for modal */
        .product-form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(90, 142, 247, 0.2);
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            min-height: 100px;
            resize: vertical;
            transition: border-color 0.3s;
        }
        
        .form-group textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(90, 142, 247, 0.2);
        }
        
        .tabs {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .tab-content {
            display: none;
            padding: 15px 0;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .image-upload-container {
            border: 2px dashed #ddd;
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .image-upload-container:hover {
            border-color: var(--primary-color);
            background-color: #f0f6ff;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 15px;
            display: none;
            border-radius: 6px;
        }
        
        /* When modal is open, prevent scrolling on the background */
        body.modal-open {
            overflow: hidden;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-bar {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
            
            .products-table th:nth-child(3),
            .products-table td:nth-child(3) {
                display: none;
            }
            
            .container {
                padding: 20px;
                margin: 15px;
                width: auto;
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

        .stock-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stock-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .stock-badge.empty {
            background-color: #ffebee;
            color: #c62828;
            animation: pulse 2s infinite;
        }

        .stock-badge.low {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .stock-badge i {
            margin-right: 4px;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
            100% {
                opacity: 1;
            }
        }

        /* Update table column width for stock */
        .products-table th:nth-child(4),
        .products-table td:nth-child(4) {
            width: 100px;
        }

        .variation-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .variation-name {
            flex: 2;
        }

        .variation-price, .variation-stock {
            flex: 1;
        }

        .remove-variation {
            padding: 8px !important;
            height: 38px;
        }
    </style>
</head>
<body>
<!-- Add this right after the <body> tag -->
<header class="header">
    <div class="d-flex align-items-center">
        <!-- Toggle Button -->
        <button id="navbarToggle" class="navbar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Brand/Logo can go here -->
        <h2>Toko Online</h2>
    </div>
</header>

<script>
        // Define the toggleSidebar function globally
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const isMobile = window.innerWidth <= 768;
            
            if (sidebar && mainContent) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save sidebar state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
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

        // Add this to your DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function() {
            // Restore sidebar state
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarCollapsed');
            
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            // Add click event listener to close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth <= 768;
                if (isMobile && !event.target.closest('.sidebar') && !event.target.closest('.navbar-toggle')) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            });
            
            // Update sidebar state on window resize
            window.addEventListener('resize', function() {
                const isMobile = window.innerWidth <= 768;
                if (!isMobile) {
                    sidebar.style.transform = '';
                }
            });
        });
    </script>

    <!-- Sidebar -->
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
            <a href="edit_product.php" style="text-decoration: none;"><div class="sidebar-item active">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </div></a>
            <a href="orders.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </div></a>
            <div class="sidebar-heading">Reports</div>
            <a href="analytics.php" style="text-decoration: none;"><div class="sidebar-item">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </div></a>
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

        <h1>Kelola Produk</h1>        

        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="action-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProducts()">
            </div>
            <div>
                <button class="btn btn-primary" id="addProductBtn">
                    <i class="fas fa-plus"></i> Tambah Produk Baru
                </button>
            </div>
        </div>
        
        <?php if (count($products) > 0): ?>
            <div class="table-responsive">
                <table class="products-table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th> <!-- Add stock column -->
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         class="product-img"
                                         onerror="this.src='assets/images/placeholder.png'">
                                </td>
                                <td class="product-name"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                <td class="product-stock">
                                    <div class="stock-wrapper">
                                        <?php 
                                        $stock = (int)$product['stock'];
                                        if ($stock <= 0) {
                                            echo '<span class="stock-badge empty"><i class="fas fa-exclamation-circle"></i> Habis</span>';
                                        } elseif ($stock < 5) {
                                            echo '<span class="stock-badge low"><i class="fas fa-exclamation-triangle"></i> ' . $stock . '</span>';
                                        } else {
                                            echo '<span class="stock-badge">' . $stock . '</span>';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn edit-btn" data-id="<?php echo $product['id']; ?>" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-id="<?php echo $product['id']; ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Belum ada produk</h3>
                <p>Mulai tambahkan produk pertama Anda</p>
                <button id="emptyStateAddBtn" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Produk Sekarang
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Add/Edit Product Modal -->
    <div class="modal-overlay" id="productModalOverlay">
        <div class="modal" id="productModal">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Produk Baru</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="productForm" class="product-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="productId" name="product_id" value="">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="productName">Nama Produk:</label>
                        <input type="text" id="productName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="productPrice">Harga:</label>
                        <input type="number" id="productPrice" name="price" step="0.01" min="0" required>
                    </div>

                    <!-- Add this new form group for stock -->
                    <div class="form-group">
                        <label for="productStock">Stok:</label>
                        <input type="number" id="productStock" name="stock" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="productDescription">Deskripsi:</label>
                        <textarea id="productDescription" name="description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Gambar Produk:</label>
                        <div class="tabs">
                            <div class="tab active" data-tab="upload">Upload File</div>
                            <div class="tab" data-tab="url">URL Gambar</div>
                        </div>
                        
                        <div id="upload-tab" class="tab-content active">
                            <div class="image-upload-container" id="dropArea">
                                <p>Klik di sini untuk upload gambar atau drag and drop file</p>
                                <input type="file" id="fileInput" name="image_file" accept="image/*" style="display: none;">
                                <img id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                        </div>
                        
                        <div id="url-tab" class="tab-content">
                            <input type="text" id="imageUrl" name="image_url" placeholder="Masukkan URL gambar">
                            <button type="button" id="previewUrlBtn" class="btn btn-secondary" style="margin-top: 10px;">Preview</button>
                            <img id="urlImagePreview" class="image-preview" alt="Preview">
                        </div>
                    </div>
                    
                    <input type="hidden" id="imageSource" name="image_source" value="upload">
                    
                    <div class="form-group">
                        <label for="productVariations">Variasi Produk:</label>
                        <div id="variationContainer">
                            <div class="variation-item">
                                <input type="text" name="variation_name[]" placeholder="Nama Variasi (mis: 1 Pack)" class="variation-name">
                                <input type="number" name="variation_price[]" placeholder="Harga" class="variation-price">
                                <input type="number" name="variation_stock[]" placeholder="Stok" class="variation-stock">
                                <button type="button" class="btn btn-danger remove-variation"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <button type="button" id="addVariation" class="btn btn-secondary mt-2">
                            <i class="fas fa-plus"></i> Tambah Variasi
                        </button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-cancel">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModalOverlay">
        <div class="modal" id="deleteModal">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                <p>Tindakan ini tidak dapat dibatalkan.</p>
                
                <form id="deleteForm" method="POST">
                    <input type="hidden" id="deleteProductId" name="product_id" value="">
                    <input type="hidden" name="action" value="delete">
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-cancel">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const productModalOverlay = document.getElementById('productModalOverlay');
        const deleteModalOverlay = document.getElementById('deleteModalOverlay');
        const productForm = document.getElementById('productForm');
        const deleteForm = document.getElementById('deleteForm');
        const modalTitle = document.getElementById('modalTitle');
        const productId = document.getElementById('productId');
        const action = document.getElementById('action');
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');
        const imageUrl = document.getElementById('imageUrl');
        const urlImagePreview = document.getElementById('urlImagePreview');
        const imageSource = document.getElementById('imageSource');
        
        // Modal Functions
        function openProductModal(isEdit = false, id = null) {
            resetForm();
            
            if (isEdit && id) {
                modalTitle.textContent = 'Edit Produk';
                productId.value = id;
                action.value = 'edit';
                // Fetch product data and populate form
                fetchProductData(id);
            } else {
                modalTitle.textContent = 'Tambah Produk Baru';
                productId.value = '';
                action.value = 'add';
            }
            
            productModalOverlay.classList.add('active');
            document.body.classList.add('modal-open');
        }
        
        function openDeleteModal(id) {
            document.getElementById('deleteProductId').value = id;
            deleteModalOverlay.classList.add('active');
            document.body.classList.add('modal-open');
        }
        
        function closeModal(overlay) {
            overlay.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
        
        function resetForm() {
            productForm.reset();
            imagePreview.style.display = 'none';
            urlImagePreview.style.display = 'none';
            
            // Reset tabs
            document.querySelector('.tab[data-tab="upload"]').click();
            imageSource.value = 'upload';
        }
        
        // Fetch product data for editing
        function fetchProductData(id) {
            fetch(`?action=get_product&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        document.getElementById('productName').value = product.name;
                        document.getElementById('productPrice').value = product.price;
                        document.getElementById('productStock').value = product.stock || 0; // Add this line
                        document.getElementById('productDescription').value = product.description || '';
                        
                        if (product.image_url) {
                            // Check if it's a URL or file path
                            if (product.image_url.startsWith('http')) {
                                document.querySelector('.tab[data-tab="url"]').click();
                                document.getElementById('imageUrl').value = product.image_url;
                                imageSource.value = 'url';
                                previewUrlImage();
                            } else {
                                // Local file
                                document.querySelector('.tab[data-tab="upload"]').click();
                                imageSource.value = 'upload';
                                // Just show the current image
                                imagePreview.src = product.image_url;
                                imagePreview.style.display = 'block';
                            }
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching product data:', error);
                    alert('Terjadi kesalahan saat mengambil data produk.');
                });
        }
        
        // Image preview functions
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function previewUrlImage() {
            const url = imageUrl.value;
            
            if (url) {
                urlImagePreview.src = url;
                urlImagePreview.style.display = 'block';
                
                urlImagePreview.onerror = function() {
                    alert('URL gambar tidak valid atau tidak dapat diakses');
                    urlImagePreview.style.display = 'none';
                };
            }
        }
        
        // Search function
        function searchProducts() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('productsTable');
            
            if (!table) return;
            
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[1];
                
                if (nameCell) {
                    const textValue = nameCell.textContent || nameCell.innerText;
                    
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }
        
        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add product button
            document.getElementById('addProductBtn').addEventListener('click', function() {
                openProductModal(false);
            });
            
            // Empty state add button
            const emptyStateAddBtn = document.getElementById('emptyStateAddBtn');
            if (emptyStateAddBtn) {
                emptyStateAddBtn.addEventListener('click', function() {
                    openProductModal(false);
                });
            }
            
            // Edit buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    openProductModal(true, id);
                });
            });
            
            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    openDeleteModal(id);
                });
            });
            
            // Close buttons and cancel buttons
            document.querySelectorAll('.modal-close, .modal-cancel').forEach(button => {
                button.addEventListener('click', function() {
                    const overlay = this.closest('.modal-overlay');
                    closeModal(overlay);
                });
            });
            
            // Close modal when clicking on overlay
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this);
                    }
                });
            });
            
            // Tab switching
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    
                    // Update active tab
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show corresponding content
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                    document.getElementById(tabName + '-tab').classList.add('active');
                    
                    // Update image source value
                    imageSource.value = tabName;
                });
            });
            
            // Image upload
            dropArea.addEventListener('click', function() {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', function() {
                previewImage(this);
            });
            
            // URL image preview
            document.getElementById('previewUrlBtn').addEventListener('click', previewUrlImage);
            
            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropArea.classList.add('highlight');
            }
            
            function unhighlight() {
                dropArea.classList.remove('highlight');
            }
            
            dropArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length) {
                    fileInput.files = files;
                    previewImage(fileInput);
                }
            }

            document.getElementById('addVariation').addEventListener('click', function() {
                const container = document.getElementById('variationContainer');
                const newVariation = document.createElement('div');
                newVariation.className = 'variation-item';
                newVariation.innerHTML = `
                    <input type="text" name="variation_name[]" placeholder="Nama Variasi" class="variation-name">
                    <input type="number" name="variation_price[]" placeholder="Harga" class="variation-price">
                    <input type="number" name="variation_stock[]" placeholder="Stok" class="variation-stock">
                    <button type="button" class="btn btn-danger remove-variation"><i class="fas fa-times"></i></button>
                `;
                container.appendChild(newVariation);
            });

            document.getElementById('variationContainer').addEventListener('click', function(e) {
                if (e.target.closest('.remove-variation')) {
                    e.target.closest('.variation-item').remove();
                }
            });
        });
    </script>
</body>
</html>
