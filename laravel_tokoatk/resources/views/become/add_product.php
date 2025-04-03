<?php
// add_product.php - Handles adding products to the database

// Database connection
$host = 'localhost';
$dbname = 'tokoonline';
$username = 'root'; // Change as needed
$password = ''; // Change as needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ];
    echo json_encode($response);
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $harga = filter_var($_POST['harga'] ?? 0, FILTER_VALIDATE_INT);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $stok = filter_var($_POST['stok'] ?? 0, FILTER_VALIDATE_INT);
    
    // Validate required fields
    if (empty($nama_produk) || $harga <= 0 || $stok < 0) {
        $response = [
            'success' => false,
            'message' => 'Semua field harus diisi dengan benar.'
        ];
        echo json_encode($response);
        exit;
    }
    
    // Handle image upload
    $gambar_path = ''; // Default empty path
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;
        
        // Check if file is an actual image
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if ($check === false) {
            $response = [
                'success' => false,
                'message' => 'File yang diunggah bukan gambar.'
            ];
            echo json_encode($response);
            exit;
        }
        
        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $gambar_path = $target_file;
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal mengunggah gambar.'
            ];
            echo json_encode($response);
            exit;
        }
    }
    
    // Insert into database
    try {
        $sql = "INSERT INTO products (nama_produk, harga, deskripsi, stok, gambar, created_at) 
                VALUES (:nama_produk, :harga, :deskripsi, :stok, :gambar, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama_produk', $nama_produk);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':gambar', $gambar_path);
        $stmt->execute();
        
        $response = [
            'success' => true,
            'message' => 'Produk berhasil ditambahkan.',
            'product_id' => $pdo->lastInsertId()
        ];
        
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
}
?>