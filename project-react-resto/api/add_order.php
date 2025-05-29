<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['menu_id', 'nama_menu', 'harga', 'quantity'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
        exit();
    }
}

// Sanitize and validate input
$menu_id = filter_var($input['menu_id'], FILTER_VALIDATE_INT);
$nama_menu = trim($input['nama_menu']);
$harga = filter_var($input['harga'], FILTER_VALIDATE_FLOAT);
$quantity = filter_var($input['quantity'], FILTER_VALIDATE_INT);
$customer_name = isset($input['customer_name']) ? trim($input['customer_name']) : 'Guest User';
$customer_phone = isset($input['customer_phone']) ? trim($input['customer_phone']) : '';
$notes = isset($input['notes']) ? trim($input['notes']) : '';

// Validate data
if ($menu_id === false || $menu_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid menu ID']);
    exit();
}

if ($harga === false || $harga <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid price']);
    exit();
}

if ($quantity === false || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

if (empty($nama_menu)) {
    echo json_encode(['success' => false, 'message' => 'Menu name cannot be empty']);
    exit();
}

try {
    // Check if menu exists
    $stmt = $pdo->prepare("SELECT id, nama, harga FROM menu WHERE id = ?");
    $stmt->execute([$menu_id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$menu) {
        echo json_encode(['success' => false, 'message' => 'Menu not found']);
        exit();
    }
    
    // Calculate total
    $total_harga = $harga * $quantity;
    
    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            menu_id, 
            nama_menu, 
            harga_satuan, 
            quantity, 
            total_harga, 
            customer_name, 
            customer_phone, 
            notes, 
            status, 
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $result = $stmt->execute([
        $menu_id,
        $nama_menu,
        $harga,
        $quantity,
        $total_harga,
        $customer_name,
        $customer_phone,
        $notes
    ]);
    
    if ($result) {
        $order_id = $pdo->lastInsertId();
        echo json_encode([
            'success' => true, 
            'message' => 'Order berhasil ditambahkan',
            'data' => [
                'order_id' => $order_id,
                'menu_id' => $menu_id,
                'nama_menu' => $nama_menu,
                'quantity' => $quantity,
                'total_harga' => $total_harga,
                'customer_name' => $customer_name
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
