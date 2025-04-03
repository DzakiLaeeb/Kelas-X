<?php
// delete_product.php - Handles deleting products
session_start();
require_once 'db_connection.php'; // Include your database connection

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $error = "ID produk tidak valid.";
    
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    } else {
        $_SESSION['error'] = $error;
        header("Location: manage_products.php");
        exit;
    }
}

$id = intval($_GET['id']);

try {
    // First, get the product to check if we need to delete an image file
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete the product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        // If product had an image and it's stored locally, delete the file
        if (!empty($product['image_url']) && file_exists($product['image_url']) && strpos($product['image_url'], 'uploads/') === 0) {
            unlink($product['image_url']);
        }
        
        $success_message = "Produk berhasil dihapus.";
        
        if (isset($_GET['ajax'])) {
            echo json_encode(['success' => true, 'message' => $success_message]);
            exit;
        } else {
            $_SESSION['success'] = $success_message;
            header("Location: manage_products.php");
            exit;
        }
    } else {
        $error = "Produk tidak ditemukan.";
        
        if (isset($_GET['ajax'])) {
            echo json_encode(['success' => false, 'message' => $error]);
            exit;
        } else {
            $_SESSION['error'] = $error;
            header("Location: manage_products.php");
            exit;
        }
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    } else {
        $_SESSION['error'] = $error;
        header("Location: manage_products.php");
        exit;
    }
}
?>