<?php
// Mengaktifkan CORS dan error reporting
require_once 'config.php';

// Validasi method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, null, 'Method not allowed. Use POST.');
    exit;
}

// Ambil data dari request
$data = getRequestData();

// Debug: Log received data
error_log('Register received data: ' . json_encode($data));

// Validasi data yang diperlukan
if (!$data) {
    sendResponse(400, null, 'No data received.');
    exit;
}

// Validasi field yang diperlukan
$requiredFields = ['name', 'username', 'email', 'password'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    sendResponse(400, null, 'Missing required fields: ' . implode(', ', $missingFields));
    exit;
}

// Sanitasi data
$name = htmlspecialchars(trim($data['name']));
$username = htmlspecialchars(trim($data['username']));
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];
$role = isset($data['role']) ? htmlspecialchars(trim($data['role'])) : 'user';

// Validasi data setelah sanitasi
if (strlen($name) < 3) {
    sendResponse(400, null, 'Nama harus minimal 3 karakter.');
    exit;
}

if (strlen($username) < 3) {
    sendResponse(400, null, 'Username harus minimal 3 karakter.');
    exit;
}

// Validasi username hanya boleh huruf, angka, dan underscore
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    sendResponse(400, null, 'Username hanya boleh mengandung huruf, angka, dan underscore.');
    exit;
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(400, null, 'Format email tidak valid.');
    exit;
}

// Validasi password
if (strlen($password) < 6) {
    sendResponse(400, null, 'Password harus minimal 6 karakter.');
    exit;
}

// Validasi role
$allowedRoles = ['admin', 'user', 'koki', 'kasir'];
if (!in_array($role, $allowedRoles)) {
    $role = 'user'; // Default ke user jika role tidak valid
}

try {
    // Koneksi ke database
    $conn = getConnection();
    
    // Cek apakah username atau email sudah digunakan
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $existingUser = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        
        if ($existingUser['username'] === $username) {
            sendResponse(409, null, 'Username sudah digunakan.');
        } else {
            sendResponse(409, null, 'Email sudah digunakan.');
        }
        exit;
    }
    
    $stmt->close();
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Siapkan timestamp
    $now = date('Y-m-d H:i:s');
    
    // Insert user baru
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("sssssss", $name, $username, $email, $hashedPassword, $role, $now, $now);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $userId = $conn->insert_id;
    $stmt->close();
    
    // Ambil data user yang baru dibuat (tanpa password)
    $stmt = $conn->prepare("SELECT id, name, username, email, role, created_at, updated_at FROM users WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    
    if (!$user) {
        throw new Exception('User not found after insert');
    }
    
    // Generate token untuk user baru
    $token = bin2hex(random_bytes(32));
    
    // Kirim response sukses
    sendResponse(201, [
        'user' => $user,
        'token' => $token
    ], 'Pendaftaran berhasil!');
    
} catch (Exception $e) {
    error_log('Registration error: ' . $e->getMessage());
    sendResponse(500, null, 'Terjadi kesalahan server. Silakan coba lagi.');
    exit;
}
?>
