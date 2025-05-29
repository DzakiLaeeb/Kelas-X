<?php
/**
 * Use Existing Database (apirestoran)
 * Script untuk menggunakan database yang sudah ada dan menambahkan tabel orders
 */

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'apirestoran';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔄 Using Existing Database: apirestoran</h2>";
    echo "<hr>";
    
    // Check existing tables
    echo "📝 <strong>Step 1: Checking existing tables...</strong><br>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✅ Found tables: " . implode(', ', $tables) . "<br><br>";
    
    // Check if orders table exists
    $orders_exists = in_array('orders', $tables);
    
    if (!$orders_exists) {
        echo "📝 <strong>Step 2: Creating orders table...</strong><br>";
        $createOrdersSQL = "
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                menu_id INT NOT NULL,
                nama_menu VARCHAR(255) NOT NULL,
                harga_satuan DECIMAL(10,2) NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                total_harga DECIMAL(10,2) NOT NULL,
                customer_name VARCHAR(255) NOT NULL DEFAULT 'Guest User',
                customer_phone VARCHAR(20) DEFAULT '',
                notes TEXT DEFAULT '',
                status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_menu_id (menu_id),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at),
                INDEX idx_customer_name (customer_name)
            )
        ";
        $pdo->exec($createOrdersSQL);
        echo "✅ Tabel 'orders' berhasil dibuat!<br><br>";
        
        // Insert sample orders
        echo "📝 <strong>Step 3: Adding sample orders...</strong><br>";
        $ordersData = [
            [1, 'Sample Menu 1', 25000.00, 2, 50000.00, 'John Doe', '081234567890', 'Pedas sedang', 'pending'],
            [2, 'Sample Menu 2', 30000.00, 1, 30000.00, 'Jane Smith', '081234567891', 'Tanpa sambal', 'confirmed'],
            [1, 'Sample Menu 1', 25000.00, 1, 25000.00, 'Bob Wilson', '081234567892', '', 'ready']
        ];
        
        $insertOrdersSQL = "INSERT INTO orders (menu_id, nama_menu, harga_satuan, quantity, total_harga, customer_name, customer_phone, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertOrdersSQL);
        
        foreach ($ordersData as $data) {
            $stmt->execute($data);
        }
        echo "✅ Data sample orders berhasil ditambahkan!<br><br>";
    } else {
        echo "✅ Tabel 'orders' sudah ada!<br><br>";
    }
    
    // Update API files to use apirestoran
    echo "📝 <strong>Step 4: Updating API configuration...</strong><br>";
    
    // Create updated get_menu.php for apirestoran
    $get_menu_content = '<?php
header(\'Content-Type: application/json\');
header(\'Access-Control-Allow-Origin: *\');
header(\'Access-Control-Allow-Methods: GET, OPTIONS\');
header(\'Access-Control-Allow-Headers: Content-Type\');

if ($_SERVER[\'REQUEST_METHOD\'] === \'OPTIONS\') {
    http_response_code(200);
    exit();
}

$host = \'localhost\';
$dbname = \'apirestoran\';
$username = \'root\';
$password = \'\';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database connection failed: \' . $e->getMessage()]);
    exit();
}

try {
    $sql = "SELECT * FROM menus ORDER BY menu ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $formatted_menus = array_map(function($menu) {
        return [
            \'id\' => (int)$menu[\'id\'],
            \'nama\' => $menu[\'menu\'],
            \'name\' => $menu[\'menu\'],
            \'deskripsi\' => $menu[\'deskripsi\'],
            \'description\' => $menu[\'deskripsi\'],
            \'harga\' => (float)$menu[\'harga\'],
            \'price\' => (float)$menu[\'harga\'],
            \'kategori_id\' => (int)$menu[\'kategori_id\'],
            \'kategori_nama\' => \'Kategori \' . $menu[\'kategori_id\'],
            \'category\' => \'Kategori \' . $menu[\'kategori_id\'],
            \'image\' => $menu[\'gambar\'] ? (strpos($menu[\'gambar\'], \'http\') === 0 ? $menu[\'gambar\'] : \'http://\' . $_SERVER[\'HTTP_HOST\'] . \'/project-react-resto/uploads/\' . $menu[\'gambar\']) : \'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop\',
            \'rating\' => 4.5,
            \'is_available\' => true,
            \'created_at\' => $menu[\'created_at\'] ?? date(\'Y-m-d H:i:s\'),
            \'preparationTime\' => \'15-20 min\',
            \'calories\' => 350,
            \'isNew\' => false,
            \'isPopular\' => true,
            \'ingredients\' => [\'Fresh ingredients\'],
            \'allergens\' => []
        ];
    }, $menus);
    
    echo json_encode([
        \'success\' => true,
        \'data\' => $formatted_menus,
        \'total\' => count($formatted_menus)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database error: \' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Error: \' . $e->getMessage()]);
}
?>';
    
    file_put_contents('api/get_menu_apirestoran.php', $get_menu_content);
    echo "✅ Created api/get_menu_apirestoran.php<br>";
    
    // Create updated add_order.php for apirestoran
    $add_order_content = '<?php
header(\'Content-Type: application/json\');
header(\'Access-Control-Allow-Origin: *\');
header(\'Access-Control-Allow-Methods: POST, OPTIONS\');
header(\'Access-Control-Allow-Headers: Content-Type\');

if ($_SERVER[\'REQUEST_METHOD\'] === \'OPTIONS\') {
    http_response_code(200);
    exit();
}

if ($_SERVER[\'REQUEST_METHOD\'] !== \'POST\') {
    http_response_code(405);
    echo json_encode([\'success\' => false, \'message\' => \'Method not allowed\']);
    exit();
}

$host = \'localhost\';
$dbname = \'apirestoran\';
$username = \'root\';
$password = \'\';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database connection failed: \' . $e->getMessage()]);
    exit();
}

$input = json_decode(file_get_contents(\'php://input\'), true);

$required_fields = [\'menu_id\', \'nama_menu\', \'harga\', \'quantity\'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode([\'success\' => false, \'message\' => "Field \'$field\' is required"]);
        exit();
    }
}

$menu_id = filter_var($input[\'menu_id\'], FILTER_VALIDATE_INT);
$nama_menu = trim($input[\'nama_menu\']);
$harga = filter_var($input[\'harga\'], FILTER_VALIDATE_FLOAT);
$quantity = filter_var($input[\'quantity\'], FILTER_VALIDATE_INT);
$customer_name = isset($input[\'customer_name\']) ? trim($input[\'customer_name\']) : \'Guest User\';
$customer_phone = isset($input[\'customer_phone\']) ? trim($input[\'customer_phone\']) : \'\';
$notes = isset($input[\'notes\']) ? trim($input[\'notes\']) : \'\';

if ($menu_id === false || $menu_id <= 0) {
    echo json_encode([\'success\' => false, \'message\' => \'Invalid menu ID\']);
    exit();
}

if ($harga === false || $harga <= 0) {
    echo json_encode([\'success\' => false, \'message\' => \'Invalid price\']);
    exit();
}

if ($quantity === false || $quantity <= 0) {
    echo json_encode([\'success\' => false, \'message\' => \'Invalid quantity\']);
    exit();
}

try {
    $total_harga = $harga * $quantity;
    
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, \'pending\', NOW())
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
            \'success\' => true, 
            \'message\' => \'Order berhasil ditambahkan\',
            \'data\' => [
                \'order_id\' => $order_id,
                \'menu_id\' => $menu_id,
                \'nama_menu\' => $nama_menu,
                \'quantity\' => $quantity,
                \'total_harga\' => $total_harga,
                \'customer_name\' => $customer_name
            ]
        ]);
    } else {
        echo json_encode([\'success\' => false, \'message\' => \'Failed to create order\']);
    }
    
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database error: \' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Error: \' . $e->getMessage()]);
}
?>';
    
    file_put_contents('api/add_order_apirestoran.php', $add_order_content);
    echo "✅ Created api/add_order_apirestoran.php<br>";
    
    // Create updated get_orders.php for apirestoran
    $get_orders_content = '<?php
header(\'Content-Type: application/json\');
header(\'Access-Control-Allow-Origin: *\');
header(\'Access-Control-Allow-Methods: GET, OPTIONS\');
header(\'Access-Control-Allow-Headers: Content-Type\');

if ($_SERVER[\'REQUEST_METHOD\'] === \'OPTIONS\') {
    http_response_code(200);
    exit();
}

$host = \'localhost\';
$dbname = \'apirestoran\';
$username = \'root\';
$password = \'\';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database connection failed: \' . $e->getMessage()]);
    exit();
}

try {
    $status = isset($_GET[\'status\']) ? $_GET[\'status\'] : \'\';
    $customer_name = isset($_GET[\'customer_name\']) ? $_GET[\'customer_name\'] : \'\';
    $limit = isset($_GET[\'limit\']) ? (int)$_GET[\'limit\'] : 50;
    $offset = isset($_GET[\'offset\']) ? (int)$_GET[\'offset\'] : 0;
    
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
    
    $count_sql = "SELECT COUNT(*) as total FROM orders $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_orders = $count_stmt->fetch(PDO::FETCH_ASSOC)[\'total\'];
    
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
            m.gambar as menu_image
        FROM orders o
        LEFT JOIN menus m ON o.menu_id = m.id
        $where_clause
        ORDER BY o.created_at DESC
        LIMIT $limit OFFSET $offset
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $formatted_orders = array_map(function($order) {
        return [
            \'id\' => (int)$order[\'id\'],
            \'menu_id\' => (int)$order[\'menu_id\'],
            \'nama_menu\' => $order[\'nama_menu\'],
            \'harga_satuan\' => (float)$order[\'harga_satuan\'],
            \'quantity\' => (int)$order[\'quantity\'],
            \'total_harga\' => (float)$order[\'total_harga\'],
            \'customer_name\' => $order[\'customer_name\'],
            \'customer_phone\' => $order[\'customer_phone\'],
            \'notes\' => $order[\'notes\'],
            \'status\' => $order[\'status\'],
            \'menu_image\' => $order[\'menu_image\'] ? (strpos($order[\'menu_image\'], \'http\') === 0 ? $order[\'menu_image\'] : \'http://\' . $_SERVER[\'HTTP_HOST\'] . \'/project-react-resto/uploads/\' . $order[\'menu_image\']) : null,
            \'created_at\' => $order[\'created_at\'],
            \'updated_at\' => $order[\'updated_at\'],
            \'formatted_price\' => \'Rp \' . number_format($order[\'total_harga\'], 0, \',\', \'.\'),
            \'formatted_date\' => date(\'d/m/Y H:i\', strtotime($order[\'created_at\']))
        ];
    }, $orders);
    
    echo json_encode([
        \'success\' => true,
        \'data\' => $formatted_orders,
        \'pagination\' => [
            \'total\' => (int)$total_orders,
            \'limit\' => $limit,
            \'offset\' => $offset,
            \'has_more\' => ($offset + $limit) < $total_orders
        ]
    ]);
    
} catch (PDOException $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Database error: \' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode([\'success\' => false, \'message\' => \'Error: \' . $e->getMessage()]);
}
?>';
    
    file_put_contents('api/get_orders_apirestoran.php', $get_orders_content);
    echo "✅ Created api/get_orders_apirestoran.php<br><br>";
    
    // Show summary
    echo "<hr>";
    echo "<h3>📊 Summary:</h3>";
    
    $tables = [\'menus\', \'orders\'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)[\'count\'];
        echo "✅ Tabel <strong>$table</strong>: $count records<br>";
    }
    
    echo "<br><hr>";
    echo "<h3>🎉 Setup Selesai!</h3>";
    echo "<p><strong>Database \'apirestoran\' sudah siap digunakan!</strong></p>";
    echo "<ul>";
    echo "<li>✅ Menggunakan database apirestoran yang sudah ada</li>";
    echo "<li>✅ Tabel orders sudah ditambahkan</li>";
    echo "<li>✅ API files untuk apirestoran sudah dibuat</li>";
    echo "</ul>";
    
    echo "<h4>🔗 Test Links (menggunakan apirestoran):</h4>";
    echo "<ul>";
    echo "<li><a href=\'api/get_menu_apirestoran.php\' target=\'_blank\'>📡 API Get Menu (apirestoran)</a></li>";
    echo "<li><a href=\'api/get_orders_apirestoran.php\' target=\'_blank\'>📡 API Get Orders (apirestoran)</a></li>";
    echo "</ul>";
    
    echo "<p><strong>Untuk menggunakan database apirestoran:</strong></p>";
    echo "<ol>";
    echo "<li>Ganti URL API di frontend dari <code>/api/get_menu.php</code> ke <code>/api/get_menu_apirestoran.php</code></li>";
    echo "<li>Ganti URL API di frontend dari <code>/api/add_order.php</code> ke <code>/api/add_order_apirestoran.php</code></li>";
    echo "<li>Ganti URL API di frontend dari <code>/api/get_orders.php</code> ke <code>/api/get_orders_apirestoran.php</code></li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style=\'color: red;\'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP sudah running</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Database \'apirestoran\' sudah ada</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p style=\'color: red;\'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: white;
}

h2, h3, h4 {
    color: white;
}

a {
    color: #4facfe;
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}

ul, ol {
    line-height: 1.8;
}

hr {
    border: none;
    height: 2px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    margin: 30px 0;
}

code {
    background-color: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
}
</style>
