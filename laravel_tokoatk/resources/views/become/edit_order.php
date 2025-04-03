<?php
require_once '../db_connection.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

try {
    $conn = getConnection();
    $order_id = $_GET['id'];
    
    $query = "
        SELECT o.*, p.name as product_name 
        FROM orders o
        LEFT JOIN products p ON o.product_id = p.id
        WHERE o.id = ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        header('Location: orders.php');
        exit;
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Edit Form Styling */
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .edit-header {
            background-color: #5a8ef7; /* Updated color for better visibility */

            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .edit-header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .edit-body {
            padding: 20px;
        }
        
        .edit-form .form-group {
            margin-bottom: 20px;
        }
        
        .edit-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .edit-form input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .edit-form input:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.2);
        }
        
        .edit-form input:read-only {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        
        .edit-footer {
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
        
        .btn-primary {
            background-color: #5a8ef7; /* Updated button color */

            color: white;
        }
        
        .btn-primary:hover {
            background-color: #375de0;
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
        
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .alert-danger {
            background-color: #f5c6cb; /* Updated alert background color */

            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .product-details {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        
        .product-details h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        
        .product-details p {
            margin: 5px 0;
            color: #6c757d;
        }
        
        .product-details strong {
            color: #333;
        }
        
        /* Calculate total section */
        .total-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }
        
        .total-price {
            font-size: 20px; /* Increased font size for better visibility */

            font-weight: 600;
            color: #4a6cf7;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1><i class="fas fa-edit"></i> Edit Order</h1>
        </div>
        
        <div class="edit-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="product-details">
                <h3>Product Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
                <p><strong>Original Price:</strong> Rp <?php echo number_format($order['product_price'], 0, ',', '.'); ?></p>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['product_id']); ?></p>
            </div>
            
            <form class="edit-form" method="post">
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" min="1" required onchange="calculateTotal()">
                </div>
                
                <div class="form-group">
                    <label for="price">Unit Price (Rp):</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($order['price']); ?>" required onchange="calculateTotal()">
                </div>
                
                <div class="total-section">
                    <p>Total: <span class="total-price" id="totalPrice">Rp <?php echo number_format($order['quantity'] * $order['price'], 0, ',', '.'); ?></span></p>
                    <input type="hidden" id="hiddenTotal" name="hiddenTotal" value="<?php echo htmlspecialchars($order['quantity'] * $order['price']); ?>">

                </div>
            
                <div class="edit-footer">
                    <a href="index.php?session_id=<?php echo htmlspecialchars($order['session_id']); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">


                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function calculateTotal() {
            const quantity = document.getElementById('quantity').value;
            const price = document.getElementById('price').value;
            const total = quantity * price;
            
            // Format the total with thousand separators
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);
            
            document.getElementById('totalPrice').textContent = formattedTotal;
        }
    </script>
</body>
</html>
