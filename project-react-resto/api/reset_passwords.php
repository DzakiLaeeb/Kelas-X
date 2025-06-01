<?php
// Reset all user passwords to default
require_once 'config.php';

// Validasi method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, null, 'Method not allowed. Use POST.');
    exit;
}

try {
    // Koneksi ke database
    $conn = getConnection();
    
    // Default passwords untuk setiap role
    $defaultPasswords = [
        'admin' => 'admin123',
        'kasir' => 'kasir123',
        'koki' => 'koki123',
        'user' => 'user123'
    ];
    
    $updated = 0;
    $errors = [];
    
    foreach ($defaultPasswords as $role => $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $now = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = ? WHERE role = ?");
        
        if ($stmt) {
            $stmt->bind_param("sss", $hashedPassword, $now, $role);
            
            if ($stmt->execute()) {
                $affectedRows = $stmt->affected_rows;
                $updated += $affectedRows;
                error_log("Reset password for role $role: $affectedRows users updated");
            } else {
                $errors[] = "Failed to reset password for role $role: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $errors[] = "Failed to prepare statement for role $role: " . $conn->error;
        }
    }
    
    $conn->close();
    
    if (empty($errors)) {
        sendResponse(200, [
            'updated_users' => $updated,
            'default_passwords' => $defaultPasswords
        ], "Successfully reset passwords for $updated users");
    } else {
        sendResponse(500, [
            'updated_users' => $updated,
            'errors' => $errors
        ], 'Some errors occurred while resetting passwords');
    }
    
} catch (Exception $e) {
    error_log('Reset passwords error: ' . $e->getMessage());
    sendResponse(500, null, 'Server error: ' . $e->getMessage());
}
?>
