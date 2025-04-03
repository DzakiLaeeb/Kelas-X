<?php
header('Content-Type: application/json');
require_once '../db_connection.php';

// Clear any previous output
ob_clean();

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $order_id = $input['order_id'] ?? null;

    if (!$order_id) {
        throw new Exception('Order ID is required');
    }

    // Delete from database
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $result = $stmt->execute([$order_id]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Order cancelled and deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete order');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit();