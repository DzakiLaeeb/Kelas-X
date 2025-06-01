<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'resto_prismatic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

try {
    // Get query parameters
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    // Build query
    $where_conditions = [];
    $params = [];

    if (!empty($status)) {
        $where_conditions[] = "status = ?";
        $params[] = $status;
    }

    if (!empty($customer_name)) {
        $where_conditions[] = "customer_name LIKE ?";
        $params[] = "%$customer_name%";
    }

    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

    // Get total count
    $count_sql = "SELECT COUNT(*) as total FROM orders $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_orders = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get orders with pagination
    $sql = "
        SELECT
            o.id,
            o.menu_id,
            o.nama_menu,
            o.harga_satuan,
            o.quantity,
            o.total_harga,
            o.customer_name,
            o.customer_phone,
            o.notes,
            o.status,
            o.created_at,
            o.updated_at,
            m.image as menu_image
        FROM orders o
        LEFT JOIN menu m ON o.menu_id = m.id
        $where_clause
        ORDER BY o.created_at DESC
        LIMIT $limit OFFSET $offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the data
    $formatted_orders = array_map(function($order) {
        return [
            'id' => (int)$order['id'],
            'menu_id' => (int)$order['menu_id'],
            'nama_menu' => $order['nama_menu'],
            'harga_satuan' => (float)$order['harga_satuan'],
            'quantity' => (int)$order['quantity'],
            'total_harga' => (float)$order['total_harga'],
            'customer_name' => $order['customer_name'],
            'customer_phone' => $order['customer_phone'],
            'notes' => $order['notes'],
            'status' => $order['status'],
            'menu_image' => $order['menu_image'],
            'created_at' => $order['created_at'],
            'updated_at' => $order['updated_at'],
            'formatted_price' => 'Rp ' . number_format($order['total_harga'], 0, ',', '.'),
            'formatted_date' => date('d/m/Y H:i', strtotime($order['created_at']))
        ];
    }, $orders);

    echo json_encode([
        'success' => true,
        'data' => $formatted_orders,
        'pagination' => [
            'total' => (int)$total_orders,
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + $limit) < $total_orders
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
