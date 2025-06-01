<?php
require_once 'config.php';

// Set content type to HTML for better display
header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Users Table</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { color: orange; }
    </style>
</head>
<body>";

echo "<h1>🔧 Setup Users Table</h1>";

try {
    $conn = getConnection();
    
    // Drop existing users table if exists
    echo "<h3>🗑️ Dropping existing users table...</h3>";
    $dropSQL = "DROP TABLE IF EXISTS users";
    if ($conn->query($dropSQL)) {
        echo "<p class='success'>✅ Existing users table dropped successfully</p>";
    } else {
        echo "<p class='error'>❌ Error dropping table: " . $conn->error . "</p>";
    }
    
    // Create new users table with complete structure
    echo "<h3>📝 Creating new users table...</h3>";
    $createTableSQL = "
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        email_verified_at TIMESTAMP NULL DEFAULT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user', 'koki', 'kasir') NOT NULL DEFAULT 'user',
        remember_token VARCHAR(100) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        INDEX idx_email (email),
        INDEX idx_username (username),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if ($conn->query($createTableSQL)) {
        echo "<p class='success'>✅ Users table created successfully</p>";
    } else {
        echo "<p class='error'>❌ Error creating table: " . $conn->error . "</p>";
        throw new Exception('Failed to create users table');
    }
    
    // Create default users
    echo "<h3>👥 Creating default users...</h3>";
    
    $defaultUsers = [
        [
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@restaurant.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin'
        ],
        [
            'name' => 'Kasir Demo',
            'username' => 'kasir',
            'email' => 'kasir@restaurant.com',
            'password' => password_hash('kasir123', PASSWORD_DEFAULT),
            'role' => 'kasir'
        ],
        [
            'name' => 'Koki Demo',
            'username' => 'koki',
            'email' => 'koki@restaurant.com',
            'password' => password_hash('koki123', PASSWORD_DEFAULT),
            'role' => 'koki'
        ],
        [
            'name' => 'User Demo',
            'username' => 'user',
            'email' => 'user@restaurant.com',
            'password' => password_hash('user123', PASSWORD_DEFAULT),
            'role' => 'user'
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($defaultUsers as $user) {
        $stmt->bind_param("sssss", $user['name'], $user['username'], $user['email'], $user['password'], $user['role']);
        
        if ($stmt->execute()) {
            echo "<p class='success'>✅ Created user: <strong>{$user['username']}</strong> ({$user['role']})</p>";
        } else {
            echo "<p class='error'>❌ Failed to create user {$user['username']}: " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    // Display created users
    echo "<h3>📋 Users in database:</h3>";
    $result = $conn->query("SELECT id, name, username, email, role, created_at FROM users ORDER BY id");
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Created At</th>";
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td><strong>{$row['username']}</strong></td>";
            echo "<td>{$row['email']}</td>";
            echo "<td><span style='background-color: #007bff; color: white; padding: 2px 8px; border-radius: 3px;'>{$row['role']}</span></td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>🔑 Default Login Credentials:</h3>";
    echo "<div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff;'>";
    echo "<p><strong>Admin:</strong> username: <code>admin</code>, password: <code>admin123</code></p>";
    echo "<p><strong>Kasir:</strong> username: <code>kasir</code>, password: <code>kasir123</code></p>";
    echo "<p><strong>Koki:</strong> username: <code>koki</code>, password: <code>koki123</code></p>";
    echo "<p><strong>User:</strong> username: <code>user</code>, password: <code>user123</code></p>";
    echo "</div>";
    
    echo "<h3>✅ Setup Complete!</h3>";
    echo "<p class='success'>Users table has been successfully created with default users.</p>";
    echo "<p><a href='../src/pages/Login.jsx' style='color: #007bff;'>← Go to Login Page</a></p>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    error_log('Setup error: ' . $e->getMessage());
}

echo "</body></html>";
?>
