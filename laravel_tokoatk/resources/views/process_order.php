<?php
session_start();
require_once 'db_connection.php';

function updateStock($conn, $product_id, $quantity) {
    try {
        $conn->beginTransaction();
        
        // Get current stock and lock the row
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
        $stmt->execute([$product_id]);
        $current_stock = $stmt->fetchColumn();
        
        if ($current_stock < $quantity) {
            $conn->rollBack();
            return false;
        }
        
        // Update stock
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}

// Use this function when processing orders
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (!updateStock($conn, $product_id, $item['quantity'])) {
            $_SESSION['error'] = "Gagal memproses pesanan. Stok tidak mencukupi.";
            header('Location: cart.php');
            exit;
        }
    }
    // Continue with order processing...
}
