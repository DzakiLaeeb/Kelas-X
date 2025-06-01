<?php
/**
 * Check Users in Database
 * Script untuk mengecek user yang ada di database
 */

// Database connection
$host = 'localhost';
$dbname = 'apirestoran';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>👥 Check Users in Database</h2>";
    echo "<hr>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $table_exists = $stmt->rowCount() > 0;
    
    if (!$table_exists) {
        echo "<div style='color: red;'>";
        echo "❌ <strong>Tabel 'users' tidak ditemukan!</strong><br>";
        echo "Silakan jalankan <a href='create_role_users.php'>create_role_users.php</a> terlebih dahulu.";
        echo "</div>";
        exit;
    }
    
    echo "✅ Tabel 'users' ditemukan!<br><br>";
    
    // Get all users
    $stmt = $pdo->query("SELECT id, name, username, email, role, created_at FROM users ORDER BY role, name");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "<div style='color: orange;'>";
        echo "⚠️ <strong>Tidak ada user di database!</strong><br>";
        echo "Silakan jalankan <a href='create_role_users.php'>create_role_users.php</a> untuk membuat user.";
        echo "</div>";
        exit;
    }
    
    echo "<h3>📊 Total Users: " . count($users) . "</h3>";
    
    // Group by role
    $usersByRole = [];
    foreach ($users as $user) {
        $usersByRole[$user['role']][] = $user;
    }
    
    // Display users by role
    foreach ($usersByRole as $role => $roleUsers) {
        $role_icon = '';
        switch($role) {
            case 'admin': $role_icon = '👤'; break;
            case 'koki': $role_icon = '👨‍🍳'; break;
            case 'kasir': $role_icon = '💰'; break;
            case 'user': $role_icon = '👥'; break;
        }
        
        echo "<h4>$role_icon Role: " . ucfirst($role) . " (" . count($roleUsers) . " users)</h4>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Created</th><th>Test Login</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($roleUsers as $user) {
            $password = '';
            switch($role) {
                case 'admin': $password = 'admin123'; break;
                case 'koki': $password = 'koki123'; break;
                case 'kasir': $password = 'kasir123'; break;
                case 'user': $password = 'user123'; break;
            }
            
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>" . ($user['username'] ?: '-') . "</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($user['created_at'])) . "</td>";
            echo "<td>";
            echo "<button onclick=\"testLogin('{$user['email']}', '$password')\" class='btn btn-sm btn-primary'>Test Login</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
        echo "<br>";
    }
    
    // Test password verification
    echo "<hr>";
    echo "<h3>🔐 Test Password Verification</h3>";
    
    foreach ($users as $user) {
        $test_password = '';
        switch($user['role']) {
            case 'admin': $test_password = 'admin123'; break;
            case 'koki': $test_password = 'koki123'; break;
            case 'kasir': $test_password = 'kasir123'; break;
            case 'user': $test_password = 'user123'; break;
        }
        
        // Get password hash from database
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $stored_password = $stmt->fetchColumn();
        
        $is_valid = password_verify($test_password, $stored_password);
        
        echo "<div style='padding: 5px; margin: 5px 0; background: " . ($is_valid ? '#d4edda' : '#f8d7da') . "; border-radius: 3px;'>";
        echo ($is_valid ? '✅' : '❌') . " <strong>{$user['name']}</strong> ({$user['email']}) - Password: $test_password";
        echo "</div>";
    }
    
    echo "<br><hr>";
    echo "<h3>🔗 Quick Actions</h3>";
    echo "<ul>";
    echo "<li><a href='create_role_users.php'>👥 Create/Update Role Users</a></li>";
    echo "<li><a href='test_login_api.php'>🔐 Test Login API</a></li>";
    echo "<li><a href='http://localhost:5176/login' target='_blank'>🌐 Frontend Login Page</a></li>";
    echo "<li><a href='http://localhost/phpmyadmin/index.php?route=/database/structure&db=apirestoran' target='_blank'>🗄️ phpMyAdmin</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>Database 'apirestoran' sudah ada</li>";
    echo "<li>XAMPP sudah running</li>";
    echo "<li>MySQL service aktif</li>";
    echo "</ul>";
}
?>

<script>
function testLogin(email, password) {
    const data = {
        username: email,
        password: password
    };
    
    fetch('http://localhost/project-react-resto/api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('✅ Login berhasil!\nUser: ' + result.data.user.name + '\nRole: ' + result.data.user.role);
        } else {
            alert('❌ Login gagal!\nError: ' + result.message);
        }
    })
    .catch(error => {
        alert('❌ Error: ' + error.message);
    });
}
</script>

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

.table {
    background: white;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    background: #e9ecef;
    font-weight: 600;
}

.btn {
    padding: 4px 8px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

hr {
    border: none;
    height: 1px;
    background: #dee2e6;
    margin: 30px 0;
}
</style>
