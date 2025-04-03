<?php
require_once '../db_connection.php';

// Only process as API endpoint
header('Content-Type: application/json');
$response = ['success' => false];

try {
    $conn = getConnection();
    
    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];
        
        $query = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute([$order_id]);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Order deleted successfully';
        } else {
            $response['message'] = 'Failed to delete order';
        }
    } else {
        $response['message'] = 'No order ID provided';
    }
} catch(PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Delete Form Styling */
        .delete-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .delete-header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .delete-header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .delete-body {
            padding: 20px;
        }
        
        .delete-message {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
        
        .delete-message i {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        .delete-message h2 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #721c24;
        }
        
        .delete-message p {
            margin: 0;
            color: #721c24;
        }
        
        .order-details {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        
        .order-details h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        
        .order-details p {
            margin: 5px 0;
            color: #6c757d;
        }
        
        .order-details strong {
            color: #333;
        }
        
        .delete-form {
            margin-top: 20px;
        }
        
        .delete-footer {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #eaeaea;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <div class="delete-header">
            <h1><i class="fas fa-trash-alt"></i> Delete Order</h1>
        </div>
        
        <div class="delete-body">
            <div class="delete-message">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Confirm Deletion</h2>
                <p>Are you sure you want to delete this order? This action cannot be undone.</p>
            </div>
            
            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Product:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                <p><strong>Price:</strong> Rp <?php echo number_format($order['price'], 0, ',', '.'); ?></p>
                <p><strong>Total:</strong> Rp <?php echo number_format($order['quantity'] * $order['price'], 0, ',', '.'); ?></p>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['product_id']); ?></p>
                <p><strong>Session ID:</strong> <?php echo htmlspecialchars($order['session_id']); ?></p>
            </div>
            
            <form class="delete-form" method="post">
                <input type="hidden" name="confirm_delete" value="1">
                
                <div class="delete-footer">
                    <a href="index.php?session_id=<?php echo htmlspecialchars($order['session_id']); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-danger delete-btn" data-id="<?php echo htmlspecialchars($order['product_id']); ?>">
                        <i class="fas fa-trash-alt"></i> Delete Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const orderId = this.getAttribute('data-id');
        
        // Make delete request without showing notifications
        fetch(`delete_order.php?id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect immediately without showing notifications
                    window.location.href = 'orders.php';
                } else {
                    console.error('Error:', data.message);
                    // Silent failure, just redirect
                    window.location.href = 'orders.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Silent failure, just redirect
                window.location.href = 'orders.php';
            });
    });
});
    </script>
</body>
</html>