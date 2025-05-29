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
    // Get menu with kategori information
    $sql = "
        SELECT
            m.id,
            m.nama,
            m.deskripsi,
            m.harga,
            m.kategori_id,
            m.image,
            m.rating,
            m.is_available,
            m.created_at,
            k.nama as kategori_nama
        FROM menu m
        LEFT JOIN kategori k ON m.kategori_id = k.id
        WHERE m.is_available = 1
        ORDER BY m.nama ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the data for frontend compatibility
    $formatted_menus = array_map(function($menu) {
        return [
            'id' => (int)$menu['id'],
            'nama' => $menu['nama'],
            'name' => $menu['nama'], // For compatibility
            'deskripsi' => $menu['deskripsi'],
            'description' => $menu['deskripsi'], // For compatibility
            'harga' => (float)$menu['harga'],
            'price' => (float)$menu['harga'], // For compatibility
            'kategori_id' => (int)$menu['kategori_id'],
            'kategori_nama' => $menu['kategori_nama'],
            'category' => $menu['kategori_nama'], // For compatibility
            'image' => $menu['image'],
            'rating' => (float)$menu['rating'],
            'is_available' => (bool)$menu['is_available'],
            'created_at' => $menu['created_at'],
            // Additional fields for frontend compatibility
            'preparationTime' => '15-20 min',
            'calories' => 350,
            'isNew' => false,
            'isPopular' => $menu['rating'] >= 4.5,
            'ingredients' => ['Fresh ingredients'],
            'allergens' => []
        ];
    }, $menus);

    echo json_encode([
        'success' => true,
        'data' => $formatted_menus,
        'total' => count($formatted_menus)
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}