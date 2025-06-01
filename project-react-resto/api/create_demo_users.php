<?php
require_once 'config.php';

try {
    $conn = getConnection();
    
    // Demo users data
    $demoUsers = [
        [
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@restaurant.com',
            'password' => 'admin123',
            'role' => 'admin'
        ],
        [
            'name' => 'Kasir Restaurant',
            'username' => 'kasir',
            'email' => 'kasir@restaurant.com',
            'password' => 'kasir123',
            'role' => 'kasir'
        ],
        [
            'name' => 'Koki Restaurant',
            'username' => 'koki',
            'email' => 'koki@restaurant.com',
            'password' => 'koki123',
            'role' => 'koki'
        ],
        [
            'name' => 'User Restaurant',
            'username' => 'user',
            'email' => 'user@restaurant.com',
            'password' => 'user123',
            'role' => 'user'
        ]
    ];
    
    $created = 0;
    $existing = 0;
    
    foreach ($demoUsers as $userData) {
        // Check if user already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $checkStmt->bind_param("ss", $userData['username'], $userData['email']);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "User {$userData['username']} already exists.\n";
            $existing++;
            $checkStmt->close();
            continue;
        }
        $checkStmt->close();
        
        // Hash password
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $now = date('Y-m-d H:i:s');
        $insertStmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssssss", 
            $userData['name'], 
            $userData['username'], 
            $userData['email'], 
            $hashedPassword, 
            $userData['role'], 
            $now, 
            $now
        );
        
        if ($insertStmt->execute()) {
            echo "User {$userData['username']} created successfully.\n";
            $created++;
        } else {
            echo "Failed to create user {$userData['username']}: " . $insertStmt->error . "\n";
        }
        $insertStmt->close();
    }
    
    $conn->close();
    
    echo "\nSummary:\n";
    echo "Created: $created users\n";
    echo "Already existing: $existing users\n";
    echo "Total demo users: " . count($demoUsers) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
