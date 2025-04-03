<?php
session_start(); // Start the session

require_once '../db_connection.php'; // Include database connection
$conn = getConnection(); // Initialize the database connection

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $session_id = $_SESSION['session_id']; // Assuming session_id is stored in session

    $query = "INSERT INTO orders (product_id, quantity, session_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $quantity, PDO::PARAM_INT);
    $stmt->bindValue(3, $session_id, PDO::PARAM_STR);
    $stmt->execute();
    echo '<p>Order created successfully!</p>';
}

// Handle Read Operation
$query = "
    SELECT o.product_id, o.quantity, o.price, p.name 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    WHERE o.session_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $_SESSION['session_id'], PDO::PARAM_STR);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Update Operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE orders SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $quantity, PDO::PARAM_INT);
    $stmt->bindValue(2, $order_id, PDO::PARAM_INT);
    $stmt->execute();
    echo '<p>Order updated successfully!</p>';
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $order_id = $_GET['id'];

    $query = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $order_id, PDO::PARAM_INT);
    $stmt->execute();
    echo '<p>Order deleted successfully!</p>';
}

// Display Orders
if (!empty($orders)) {
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Actions</th></tr></thead>';
    echo '<tbody>';
    foreach ($orders as $order) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($order['name']) . '</td>';
        echo '<td>' . htmlspecialchars($order['quantity']) . '</td>';
        echo '<td>Rp ' . number_format($order['price'], 0, ',', '.') . '</td>';
        echo '<td>
                <form action="crud_operations.php" method="POST" style="display:inline;">
                    <input type="hidden" name="order_id" value="' . $order['product_id'] . '">
                    <input type="number" name="quantity" value="' . $order['quantity'] . '" required>
                    <button type="submit" name="action" value="update" class="btn btn-warning">Update</button>
                </form>
                <a href="crud_operations.php?action=delete&id=' . $order['product_id'] . '" class="btn btn-danger">Delete</a>
              </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No orders found for this session.</p>';
}

// Form to create a new order
echo '<h2>Add New Order</h2>';
echo '<form action="crud_operations.php" method="POST">';
echo '<div class="form-group">';
echo '<label for="product_id">Product:</label>';
echo '<select name="product_id" id="product_id" class="form-control">';
$query = "SELECT id, name FROM products"; // Fetch products for the dropdown
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($products as $product) {
    echo '<option value="' . $product['id'] . '">' . htmlspecialchars($product['name']) . '</option>';
}
echo '</select>';
echo '</div>';
echo '<div class="form-group">';
echo '<label for="quantity">Quantity:</label>';
echo '<input type="number" name="quantity" id="quantity" class="form-control" required>';
echo '</div>';
echo '<div class="form-group">';
echo '<button type="submit" name="action" value="create" class="btn btn-primary">Add Order</button>';
echo '</div>';
echo '</form>';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    /* Additional styles can be added here */
</style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <!-- Existing header content... -->
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Existing sidebar content... -->
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <h1 class="page-title">Dashboard</h1>
        
        <!-- Stats Row -->
        <div class="row mb-4">
            <!-- Existing stats cards... -->
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="card">
                    <h2 class="mb-3">Sales Overview</h2>
                    <div class="chart-container" id="salesChart">
                        <!-- Existing sales chart content... -->
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <h2 class="mb-3">Traffic Sources</h2>
                    <div class="chart-container" id="trafficChart"></div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="car
</main>
</body>
</html>
