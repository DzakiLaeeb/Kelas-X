<?php
require_once 'config.php';

try {
    $conn = getConnection();
    
    // Demo users with correct passwords
    $demoUsers = [
        ['username' => 'admin', 'password' => 'admin123'],
        ['username' => 'kasir', 'password' => 'kasir123'],
        ['username' => 'koki', 'password' => 'koki123'],
        ['username' => 'user', 'password' => 'user123']
    ];
    
    echo "Fixing demo user passwords...\n\n";
    
    foreach ($demoUsers as $userData) {
        // Hash the password correctly
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Update the user's password
        $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE username = ?");
        $stmt->bind_param("ss", $hashedPassword, $userData['username']);
        
        if ($stmt->execute()) {
            echo "✅ Updated password for user: {$userData['username']}\n";
            
            // Test the password verification
            $testStmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $testStmt->bind_param("s", $userData['username']);
            $testStmt->execute();
            $result = $testStmt->get_result();
            $user = $result->fetch_assoc();
            
            if (password_verify($userData['password'], $user['password'])) {
                echo "   ✅ Password verification test passed\n";
            } else {
                echo "   ❌ Password verification test failed\n";
            }
            $testStmt->close();
            
        } else {
            echo "❌ Failed to update password for user: {$userData['username']}\n";
            echo "   Error: " . $stmt->error . "\n";
        }
        
        $stmt->close();
        echo "\n";
    }
    
    $conn->close();
    echo "Password fix completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
