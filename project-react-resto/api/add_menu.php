<?php
require_once 'config.php';
header('Content-Type: application/json');

// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a form submission with file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Handle multipart form data
    $menu = $_POST['menu'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $kategori_id = $_POST['kategori_id'] ?? 0;
    
    // Handle image upload
    $uploadResponse = handleImageUpload($_FILES['image']);
    if (!$uploadResponse['success']) {
        echo json_encode(['success' => false, 'message' => $uploadResponse['message']]);
        exit;
    }
    $gambar = $uploadResponse['filename'];
} else {
    // Handle JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['menu']) || !isset($data['deskripsi']) || !isset($data['harga']) || !isset($data['kategori_id'])) {
        echo json_encode(['success' => false, 'message' => 'Data menu tidak lengkap']);
        exit;
    }

    $menu = $data['menu'];
    $deskripsi = $data['deskripsi'];
    $gambar = isset($data['gambar']) ? $data['gambar'] : null;
    $harga = $data['harga'];
    $kategori_id = $data['kategori_id'];
}

// Function to handle image upload
function handleImageUpload($file) {
    $response = ['success' => false, 'message' => '', 'filename' => ''];
    
    try {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        
        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        
        // Validate file extension
        if (!in_array($fileExt, $allowed)) {
            throw new Exception('Invalid file type');
        }
        
        // Validate file size (5MB max)
        if ($fileSize > 5000000) {
            throw new Exception('File is too large');
        }
        
        // Check for upload errors
        if ($fileError !== 0) {
            throw new Exception('Error uploading file');
        }
        
        // Generate unique filename
        $newFileName = uniqid('menu_', true) . '.' . $fileExt;
        
        // Upload directory path
        $uploadDir = __DIR__ . '/../public/uploads/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Full path for the new file
        $uploadPath = $uploadDir . $newFileName;
        
        // Move uploaded file
        if (!move_uploaded_file($fileTmpName, $uploadPath)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        $response['success'] = true;
        $response['filename'] = $newFileName;
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    return $response;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO menus (menu, deskripsi, gambar, harga, kategori_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("sssdi", $menu, $deskripsi, $gambar, $harga, $kategori_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Menu berhasil ditambahkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambah menu: ' . $stmt->error]);
}
$stmt->close();
$conn->close();