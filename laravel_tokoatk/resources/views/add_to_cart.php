<?php
session_start();
require_once __DIR__ . '/config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT) ?? 1;
        
        // Validate required data
        if (!$product_id) {
            throw new Exception("Data produk tidak lengkap");
        }

        // Verify stock availability without decreasing it
        $stmt = $conn->prepare("SELECT stock, price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product || $product['stock'] < $quantity) {
            $_SESSION['error'] = "Stok tidak mencukupi";
            header('Location: product_detail.php?id=' . $product_id);
            exit;
        }

        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add/update cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'quantity' => $quantity,
                'price' => $product['price']
            ];
        }

        $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang";
        header('Location: cart.php');
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menambahkan ke keranjang: " . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();