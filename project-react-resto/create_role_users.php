<?php
/**
 * Create Role Users
 * Script untuk membuat user dengan role Koki dan Kasir
 */

// Database connection
$host = 'localhost';
$dbname = 'apirestoran';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>👥 Membuat User dengan Role Koki dan Kasir</h2>";
    echo "<hr>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $table_exists = $stmt->rowCount() > 0;
    
    if (!$table_exists) {
        echo "📝 <strong>Membuat tabel users...</strong><br>";
        $createUsersSQL = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'user', 'koki', 'kasir') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_email (email),
                INDEX idx_role (role)
            )
        ";
        $pdo->exec($createUsersSQL);
        echo "✅ Tabel 'users' berhasil dibuat!<br><br>";
    } else {
        echo "✅ Tabel 'users' sudah ada!<br><br>";
    }
    
    // Check current users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $current_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "📊 <strong>User saat ini:</strong> $current_count users<br><br>";
    
    // User data untuk role baru
    $userData = [
        // Admin
        ['Admin User', 'admin@resto.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
        
        // Koki
        ['Chef Budi', 'koki@resto.com', password_hash('koki123', PASSWORD_DEFAULT), 'koki'],
        ['Chef Sari', 'koki2@resto.com', password_hash('koki123', PASSWORD_DEFAULT), 'koki'],
        
        // Kasir
        ['Kasir Ani', 'kasir@resto.com', password_hash('kasir123', PASSWORD_DEFAULT), 'kasir'],
        ['Kasir Dedi', 'kasir2@resto.com', password_hash('kasir123', PASSWORD_DEFAULT), 'kasir'],
        
        // User biasa
        ['Customer User', 'user@resto.com', password_hash('user123', PASSWORD_DEFAULT), 'user']
    ];
    
    echo "📝 <strong>Menambahkan " . count($userData) . " user baru...</strong><br>";
    
    $insertUserSQL = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), role = VALUES(role)";
    $stmt = $pdo->prepare($insertUserSQL);
    
    $success_count = 0;
    foreach ($userData as $data) {
        try {
            $stmt->execute($data);
            $success_count++;
            echo "✅ Berhasil menambahkan: {$data[0]} ({$data[3]})<br>";
        } catch (PDOException $e) {
            echo "❌ Error adding user '{$data[0]}': " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<br>✅ Berhasil menambahkan/update $success_count user!<br><br>";
    
    // Show final count and users by role
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $final_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "<hr>";
    echo "<h3>📊 Summary:</h3>";
    echo "✅ Total user sekarang: <strong>$final_count users</strong><br>";
    
    // Show users by role
    $stmt = $pdo->query("
        SELECT role, COUNT(*) as jumlah 
        FROM users 
        GROUP BY role
        ORDER BY role
    ");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br><strong>User per role:</strong><br>";
    echo "<ul>";
    foreach ($roles as $role) {
        $role_icon = '';
        switch($role['role']) {
            case 'admin': $role_icon = '👤'; break;
            case 'koki': $role_icon = '👨‍🍳'; break;
            case 'kasir': $role_icon = '💰'; break;
            case 'user': $role_icon = '👥'; break;
        }
        echo "<li>$role_icon <strong>{$role['role']}</strong>: {$role['jumlah']} users</li>";
    }
    echo "</ul>";
    
    // Show all users
    $stmt = $pdo->query("SELECT name, email, role FROM users ORDER BY role, name");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br><strong>Daftar semua user:</strong><br>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Password</th></tr></thead>";
    echo "<tbody>";
    
    $passwords = [
        'admin' => 'admin123',
        'koki' => 'koki123',
        'kasir' => 'kasir123',
        'user' => 'user123'
    ];
    
    foreach ($users as $user) {
        $role_icon = '';
        switch($user['role']) {
            case 'admin': $role_icon = '👤'; break;
            case 'koki': $role_icon = '👨‍🍳'; break;
            case 'kasir': $role_icon = '💰'; break;
            case 'user': $role_icon = '👥'; break;
        }
        $password = $passwords[$user['role']] ?? 'default123';
        echo "<tr>";
        echo "<td>{$user['name']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>$role_icon {$user['role']}</td>";
        echo "<td><code>$password</code></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "</div>";
    
    echo "<br><hr>";
    echo "<h3>🎉 Setup Role Users Selesai!</h3>";
    echo "<ul>";
    echo "<li>✅ $success_count user telah ditambahkan/diupdate</li>";
    echo "<li>✅ Role admin, koki, kasir, dan user sudah tersedia</li>";
    echo "<li>✅ Password default sudah diatur</li>";
    echo "</ul>";
    
    echo "<h4>🔗 Test Login:</h4>";
    echo "<ul>";
    echo "<li><a href='http://localhost:5176/login' target='_blank'>🔐 Login Page</a> - Test login dengan user di atas</li>";
    echo "<li><strong>Admin:</strong> admin@resto.com / admin123</li>";
    echo "<li><strong>Koki:</strong> koki@resto.com / koki123</li>";
    echo "<li><strong>Kasir:</strong> kasir@resto.com / kasir123</li>";
    echo "<li><strong>User:</strong> user@resto.com / user123</li>";
    echo "</ul>";
    
    echo "<h4>🎯 Dashboard URLs:</h4>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> <a href='http://localhost:5176/admin' target='_blank'>http://localhost:5176/admin</a></li>";
    echo "<li><strong>Koki:</strong> <a href='http://localhost:5176/koki' target='_blank'>http://localhost:5176/koki</a></li>";
    echo "<li><strong>Kasir:</strong> <a href='http://localhost:5176/kasir' target='_blank'>http://localhost:5176/kasir</a></li>";
    echo "<li><strong>Public:</strong> <a href='http://localhost:5176/' target='_blank'>http://localhost:5176/</a></li>";
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
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: white;
}

h2, h3, h4 {
    color: white;
}

a {
    color: #4facfe;
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}

ul {
    line-height: 1.8;
}

hr {
    border: none;
    height: 2px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    margin: 30px 0;
}

.table {
    background: rgba(255,255,255,0.9);
    color: #333;
    border-radius: 8px;
    overflow: hidden;
}

.table th {
    background: rgba(0,0,0,0.1);
    font-weight: 600;
}

code {
    background: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
}
</style>
