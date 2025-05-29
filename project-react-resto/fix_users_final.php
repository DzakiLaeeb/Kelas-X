<?php
/**
 * Fix Users Final
 * Script final untuk memperbaiki semua user dan password
 */

// Database connection
$host = 'localhost';
$dbname = 'apirestoran';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Fix Users Final</h2>";
    echo "<hr>";
    
    // Drop and recreate users table to ensure clean state
    echo "<h3>🗑️ Reset Users Table</h3>";
    
    $pdo->exec("DROP TABLE IF EXISTS users");
    echo "✅ Dropped existing users table<br>";
    
    $createTableSQL = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            username VARCHAR(255) NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user', 'koki', 'kasir') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_email (email),
            INDEX idx_username (username),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createTableSQL);
    echo "✅ Created new users table<br><br>";
    
    // Create users with proper password hashing
    echo "<h3>👥 Create Role Users</h3>";
    
    $users = [
        [
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@resto.com',
            'password' => 'admin123',
            'role' => 'admin'
        ],
        [
            'name' => 'Chef Budi',
            'username' => 'koki',
            'email' => 'koki@resto.com',
            'password' => 'koki123',
            'role' => 'koki'
        ],
        [
            'name' => 'Chef Sari',
            'username' => 'koki2',
            'email' => 'koki2@resto.com',
            'password' => 'koki123',
            'role' => 'koki'
        ],
        [
            'name' => 'Kasir Ani',
            'username' => 'kasir',
            'email' => 'kasir@resto.com',
            'password' => 'kasir123',
            'role' => 'kasir'
        ],
        [
            'name' => 'Kasir Dedi',
            'username' => 'kasir2',
            'email' => 'kasir2@resto.com',
            'password' => 'kasir123',
            'role' => 'kasir'
        ],
        [
            'name' => 'Customer User',
            'username' => 'user',
            'email' => 'user@resto.com',
            'password' => 'user123',
            'role' => 'user'
        ]
    ];
    
    $insertSQL = "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertSQL);
    
    foreach ($users as $user) {
        // Hash password properly
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $result = $stmt->execute([
            $user['name'],
            $user['username'],
            $user['email'],
            $hashedPassword,
            $user['role']
        ]);
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 5px; margin: 5px 0; border-radius: 3px;'>";
            echo "✅ Created: <strong>{$user['name']}</strong> ({$user['email']}) - Role: {$user['role']}";
            echo "</div>";
            
            // Test password immediately
            $testValid = password_verify($user['password'], $hashedPassword);
            if (!$testValid) {
                echo "<div style='background: #f8d7da; padding: 5px; margin: 5px 0; border-radius: 3px;'>";
                echo "❌ Password test failed for {$user['email']}";
                echo "</div>";
            }
        } else {
            echo "<div style='background: #f8d7da; padding: 5px; margin: 5px 0; border-radius: 3px;'>";
            echo "❌ Failed to create: {$user['name']}";
            echo "</div>";
        }
    }
    
    echo "<br><h3>🧪 Test All Users</h3>";
    
    // Test each user login
    foreach ($users as $user) {
        echo "<h4>Testing: {$user['email']}</h4>";
        
        // Get user from database
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$user['email']]);
        $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dbUser) {
            $passwordValid = password_verify($user['password'], $dbUser['password']);
            
            echo "<div style='background: " . ($passwordValid ? '#d4edda' : '#f8d7da') . "; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
            echo ($passwordValid ? '✅' : '❌') . " <strong>Password Test:</strong> " . ($passwordValid ? 'VALID' : 'INVALID') . "<br>";
            echo "<strong>Email:</strong> {$user['email']}<br>";
            echo "<strong>Password:</strong> {$user['password']}<br>";
            echo "<strong>Role:</strong> {$dbUser['role']}<br>";
            echo "</div>";
            
            // Test API call
            $apiData = json_encode([
                'username' => $user['email'],
                'password' => $user['password']
            ]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost/project-react-resto/api/login.php');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $apiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $apiResult = json_decode($apiResponse, true);
            
            echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
            echo "<strong>API Test (HTTP $httpCode):</strong> ";
            if ($apiResult && $apiResult['success']) {
                echo "<span style='color: green;'>✅ SUCCESS</span>";
            } else {
                echo "<span style='color: red;'>❌ FAILED</span>";
                if ($apiResult) {
                    echo " - " . $apiResult['message'];
                }
            }
            echo "</div>";
        }
        
        echo "<hr>";
    }
    
    // Final summary
    echo "<h3>🎉 Setup Complete!</h3>";
    echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 5px;'>";
    echo "<h4>✅ Users Created Successfully:</h4>";
    echo "<ul>";
    foreach ($users as $user) {
        echo "<li><strong>{$user['role']}:</strong> {$user['email']} / {$user['password']}</li>";
    }
    echo "</ul>";
    
    echo "<h4>🔗 Test Login:</h4>";
    echo "<a href='http://localhost:5176/login' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🔐 Frontend Login</a>";
    echo "<a href='test_login_api.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🧪 Test API</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}

h2, h3, h4 {
    color: #333;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

hr {
    border: none;
    height: 1px;
    background: #dee2e6;
    margin: 20px 0;
}
</style>
