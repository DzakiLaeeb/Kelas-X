<?php
/**
 * Debug Login Issue
 * Script untuk debug masalah login koki dan kasir
 */

// Database connection
$host = 'localhost';
$dbname = 'apirestoran';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Debug Login Issue</h2>";
    echo "<hr>";
    
    // Test specific user: koki@resto.com
    $test_email = 'koki@resto.com';
    $test_password = 'koki123';
    
    echo "<h3>🧪 Testing: $test_email</h3>";
    
    // 1. Check if user exists
    $stmt = $pdo->prepare("SELECT id, name, username, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$test_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "<div style='color: red;'>❌ User tidak ditemukan di database!</div>";
        
        // Check all users
        echo "<h4>📋 Semua user di database:</h4>";
        $stmt = $pdo->query("SELECT id, name, email, role FROM users");
        $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($all_users)) {
            echo "<div style='color: orange;'>⚠️ Database kosong! Jalankan create_role_users.php</div>";
        } else {
            echo "<ul>";
            foreach ($all_users as $u) {
                echo "<li>{$u['name']} - {$u['email']} ({$u['role']})</li>";
            }
            echo "</ul>";
        }
        
        echo "<br><a href='create_role_users.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔧 Create Users</a>";
        exit;
    }
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ User ditemukan!<br>";
    echo "<strong>ID:</strong> {$user['id']}<br>";
    echo "<strong>Name:</strong> {$user['name']}<br>";
    echo "<strong>Email:</strong> {$user['email']}<br>";
    echo "<strong>Role:</strong> {$user['role']}<br>";
    echo "<strong>Username:</strong> " . ($user['username'] ?: 'NULL') . "<br>";
    echo "</div>";
    
    // 2. Test password verification
    echo "<h4>🔐 Test Password Verification</h4>";
    $stored_hash = $user['password'];
    $is_valid = password_verify($test_password, $stored_hash);
    
    echo "<div style='background: " . ($is_valid ? '#d4edda' : '#f8d7da') . "; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo ($is_valid ? '✅' : '❌') . " Password verification: " . ($is_valid ? 'VALID' : 'INVALID') . "<br>";
    echo "<strong>Test Password:</strong> $test_password<br>";
    echo "<strong>Stored Hash:</strong> " . substr($stored_hash, 0, 50) . "...<br>";
    echo "</div>";
    
    if (!$is_valid) {
        echo "<h4>🔧 Fix Password</h4>";
        echo "<p>Password hash tidak valid. Mari kita perbaiki:</p>";
        
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $result = $stmt->execute([$new_hash, $test_email]);
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px;'>";
            echo "✅ Password berhasil diperbaiki!<br>";
            echo "<strong>New Hash:</strong> " . substr($new_hash, 0, 50) . "...";
            echo "</div>";
            
            // Test again
            $is_valid_now = password_verify($test_password, $new_hash);
            echo "<div style='background: " . ($is_valid_now ? '#d4edda' : '#f8d7da') . "; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo ($is_valid_now ? '✅' : '❌') . " Password verification after fix: " . ($is_valid_now ? 'VALID' : 'INVALID');
            echo "</div>";
        }
    }
    
    // 3. Test API call
    echo "<h4>🌐 Test API Call</h4>";
    
    $api_data = json_encode([
        'username' => $test_email,
        'password' => $test_password
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/project-react-resto/api/login.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($api_data)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $api_response = curl_exec($ch);
    $api_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>API Request:</strong><br>";
    echo "<pre>" . htmlspecialchars($api_data) . "</pre>";
    echo "<strong>HTTP Code:</strong> $api_http_code<br>";
    echo "<strong>API Response:</strong><br>";
    echo "<pre>" . htmlspecialchars($api_response) . "</pre>";
    echo "</div>";
    
    $api_result = json_decode($api_response, true);
    if ($api_result) {
        if ($api_result['success']) {
            echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px;'>";
            echo "✅ API Login berhasil!<br>";
            echo "<strong>User:</strong> " . $api_result['data']['user']['name'] . "<br>";
            echo "<strong>Role:</strong> " . $api_result['data']['user']['role'];
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
            echo "❌ API Login gagal!<br>";
            echo "<strong>Error:</strong> " . $api_result['message'];
            echo "</div>";
        }
    }
    
    // 4. Fix all role users
    echo "<hr>";
    echo "<h3>🔧 Fix All Role Users</h3>";
    
    $role_users = [
        ['name' => 'Admin User', 'email' => 'admin@resto.com', 'password' => 'admin123', 'role' => 'admin'],
        ['name' => 'Chef Budi', 'email' => 'koki@resto.com', 'password' => 'koki123', 'role' => 'koki'],
        ['name' => 'Kasir Ani', 'email' => 'kasir@resto.com', 'password' => 'kasir123', 'role' => 'kasir'],
        ['name' => 'Customer User', 'email' => 'user@resto.com', 'password' => 'user123', 'role' => 'user']
    ];
    
    foreach ($role_users as $role_user) {
        $hash = password_hash($role_user['password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            name = VALUES(name), 
            password = VALUES(password), 
            role = VALUES(role)
        ");
        
        $result = $stmt->execute([
            $role_user['name'],
            $role_user['email'],
            $hash,
            $role_user['role']
        ]);
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 5px; margin: 5px 0; border-radius: 3px;'>";
            echo "✅ {$role_user['name']} ({$role_user['email']}) - {$role_user['role']}";
            echo "</div>";
        }
    }
    
    echo "<br><hr>";
    echo "<h3>🎯 Test Login Sekarang</h3>";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Silakan test login dengan:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Koki:</strong> koki@resto.com / koki123</li>";
    echo "<li><strong>Kasir:</strong> kasir@resto.com / kasir123</li>";
    echo "<li><strong>Admin:</strong> admin@resto.com / admin123</li>";
    echo "</ul>";
    echo "<a href='http://localhost:5176/login' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Test Login</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}

h2, h3, h4 {
    color: #333;
}

pre {
    background: #f1f1f1;
    padding: 10px;
    border-radius: 3px;
    overflow-x: auto;
    font-size: 12px;
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
    margin: 30px 0;
}
</style>
