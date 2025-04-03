<?php
session_start();
require_once 'db_connection.php';

try {
    $conn = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['cart_id'])) {
            die("Error: Cart ID is required");
        }

        $cart_id = filter_input(INPUT_POST, 'cart_id', FILTER_SANITIZE_NUMBER_INT);

        // Remove item from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = :cart_id");
        $stmt->execute(['cart_id' => $cart_id]);

        // Update cart count in session
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = :session_id");
        $stmt->execute(['session_id' => $_SESSION['cart_id']]);
        $result = $stmt->fetch();
        $_SESSION['cart_count'] = $result['total'] ?? 0;

        header("Location: cart.php");
        exit();
    }
} catch(PDOException $e) {
    handle_db_error($e, "Error removing item from cart");
}