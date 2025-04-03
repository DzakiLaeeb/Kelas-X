<?php
session_start();
require_once 'db_connection.php';

try {
    $conn = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['cart_id']) || !isset($_POST['action'])) {
            die("Error: Invalid request");
        }

        $cart_id = filter_input(INPUT_POST, 'cart_id', FILTER_SANITIZE_NUMBER_INT);
        $action = $_POST['action'];

        // Get current quantity
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = :cart_id");
        $stmt->execute(['cart_id' => $cart_id]);
        $current_item = $stmt->fetch();

        if (!$current_item) {
            die("Error: Cart item not found");
        }

        $current_quantity = $current_item['quantity'];
        $new_quantity = $current_quantity;

        // Update quantity based on action
        if ($action === 'increase') {
            $new_quantity = $current_quantity + 1;
        } else if ($action === 'decrease') {
            if ($current_quantity > 1) {
                $new_quantity = $current_quantity - 1;
            } else {
                // If quantity would become 0, remove the item
                $stmt = $conn->prepare("DELETE FROM cart WHERE id = :cart_id");
                $stmt->execute(['cart_id' => $cart_id]);
                header("Location: cart.php");
                exit();
            }
        }

        // Update quantity in database
        $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :cart_id");
        $stmt->execute([
            'quantity' => $new_quantity,
            'cart_id' => $cart_id
        ]);

        // Update cart count in session
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = :session_id");
        $stmt->execute(['session_id' => $_SESSION['cart_id']]);
        $result = $stmt->fetch();
        $_SESSION['cart_count'] = $result['total'] ?? 0;

        header("Location: cart.php");
        exit();
    }
} catch(PDOException $e) {
    handle_db_error($e, "Error updating cart");
}