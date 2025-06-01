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
error_log('Login received data: ' . json_encode($data));

// Validasi data yang diperlukan
if (!$data) {
    sendResponse(400, null, 'No data received.');
    exit;
}

// Cek apakah ada username atau email
if ((!isset($data['username']) || empty(trim($data['username']))) && 
    (!isset($data['email']) || empty(trim($data['email'])))) {
    sendResponse(400, null, 'Username atau email harus diisi.');
    exit;
}

// Validasi password
if (!isset($data['password']) || empty(trim($data['password']))) {
    sendResponse(400, null, 'Password harus diisi.');
    exit;
}

// Validasi format email jika menggunakan email
if (isset($data['email']) && !empty($data['email'])) {
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, null, 'Format email tidak valid.');
        exit;
    }
}

try {
    // Koneksi ke database
    $conn = getConnection();

    // Ambil identifier (bisa username atau email)
    $identifier = '';
    if (isset($data['username']) && !empty(trim($data['username']))) {
        $identifier = htmlspecialchars(trim($data['username']));
    } elseif (isset($data['email']) && !empty(trim($data['email']))) {
        $identifier = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    }

    // Query untuk mencari user berdasarkan username ATAU email
    $stmt = $conn->prepare("SELECT id, name, username, email, password, role, created_at, updated_at FROM users WHERE username = ? OR email = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        sendResponse(401, null, 'Username atau email tidak ditemukan.');
        exit;
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Debug: Log found user (without password)
    $userForLog = $user;
    unset($userForLog['password']);
    error_log('Found user: ' . json_encode($userForLog));

    // Verifikasi password
    if (!password_verify($data['password'], $user['password'])) {
        $conn->close();
        sendResponse(401, null, 'Password salah.');
        exit;
    }

    // Hapus password dari data user
    unset($user['password']);

    // Generate token
    $token = bin2hex(random_bytes(32));

    // Update last login time dan simpan token
    $now = date('Y-m-d H:i:s');
    $updateStmt = $conn->prepare("UPDATE users SET updated_at = ?, remember_token = ? WHERE id = ?");

    if ($updateStmt) {
        $updateStmt->bind_param("ssi", $now, $token, $user['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $conn->close();

    // Kirim response sukses
    sendResponse(200, [
        'user' => $user,
        'token' => $token
    ], 'Login berhasil!');

} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    sendResponse(500, null, 'Terjadi kesalahan server. Silakan coba lagi.');
    exit;
}
?>
