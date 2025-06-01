<?php
// Test login API
require_once 'config.php';

echo "Testing Login API...\n\n";

// Test data
$testCredentials = [
    'username' => 'admin',
    'password' => 'admin123'
];

echo "Test credentials: " . json_encode($testCredentials) . "\n\n";

try {
    $conn = getConnection();
    
    // Test database connection
    echo "✅ Database connection successful\n";
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, name, username, email, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $testCredentials['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "❌ User not found\n";
        exit;
    }
    
    $user = $result->fetch_assoc();
    echo "✅ User found: " . $user['name'] . " (" . $user['role'] . ")\n";
    
    // Test password verification
    if (password_verify($testCredentials['password'], $user['password'])) {
        echo "✅ Password verification successful\n";
        
        // Remove password from user data
        unset($user['password']);
        
        // Generate token
        $token = bin2hex(random_bytes(32));
        echo "✅ Token generated: " . substr($token, 0, 20) . "...\n";
        
        // Test response format
        $response = [
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Login berhasil!'
        ];
        
        echo "✅ Response format:\n";
        echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
        
    } else {
        echo "❌ Password verification failed\n";
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
