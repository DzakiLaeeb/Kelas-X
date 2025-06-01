<?php
/**
 * Check Database Structure
 * Script untuk mengecek struktur database yang ada
 */

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect tanpa database name untuk list databases
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Check Database Structure</h2>";
    echo "<hr>";
    
    // List all databases
    echo "<h3>📊 Available Databases:</h3>";
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>";
    foreach ($databases as $db) {
        if (!in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys'])) {
            echo "<li><strong>$db</strong></li>";
        }
    }
    echo "</ul><br>";
    
    // Check specific databases
    $target_databases = ['resto_prismatic', 'apirestoran'];
    
    foreach ($target_databases as $dbname) {
        echo "<h3>🗄️ Database: $dbname</h3>";
        
        try {
            $pdo_db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo "✅ Database '$dbname' exists!<br>";
            
            // Show tables
            $stmt = $pdo_db->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (!empty($tables)) {
                echo "<strong>Tables:</strong><br>";
                echo "<ul>";
                foreach ($tables as $table) {
                    echo "<li>$table</li>";
                }
                echo "</ul>";
                
                // Check specific tables structure
                foreach ($tables as $table) {
                    if (in_array($table, ['menu', 'menus', 'kategori', 'orders'])) {
                        echo "<h4>📋 Structure of table '$table':</h4>";
                        $stmt = $pdo_db->query("DESCRIBE $table");
                        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; margin-bottom: 15px;'>";
                        echo "<tr style='background-color: #f0f0f0;'>";
                        echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th>";
                        echo "</tr>";
                        
                        foreach ($columns as $column) {
                            echo "<tr>";
                            echo "<td><strong>{$column['Field']}</strong></td>";
                            echo "<td>{$column['Type']}</td>";
                            echo "<td>{$column['Null']}</td>";
                            echo "<td>{$column['Key']}</td>";
                            echo "<td>{$column['Default']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        
                        // Show sample data
                        $stmt = $pdo_db->query("SELECT COUNT(*) as count FROM $table");
                        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                        echo "<p><strong>Records:</strong> $count</p>";
                        
                        if ($count > 0 && $count <= 10) {
                            echo "<strong>Sample data:</strong><br>";
                            $stmt = $pdo_db->query("SELECT * FROM $table LIMIT 5");
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (!empty($data)) {
                                echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; margin-bottom: 15px; font-size: 12px;'>";
                                echo "<tr style='background-color: #f0f0f0;'>";
                                foreach (array_keys($data[0]) as $key) {
                                    echo "<th>$key</th>";
                                }
                                echo "</tr>";
                                
                                foreach ($data as $row) {
                                    echo "<tr>";
                                    foreach ($row as $value) {
                                        $display_value = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
                                        echo "<td>$display_value</td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
                        }
                        echo "<br>";
                    }
                }
            } else {
                echo "❌ No tables found in database '$dbname'<br>";
            }
            
        } catch (PDOException $e) {
            echo "❌ Database '$dbname' not found or error: " . $e->getMessage() . "<br>";
        }
        
        echo "<hr>";
    }
    
    // Recommendations
    echo "<h3>💡 Recommendations:</h3>";
    echo "<div style='background-color: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; border-left: 4px solid #4facfe;'>";
    
    // Check if resto_prismatic exists and has correct structure
    try {
        $pdo_resto = new PDO("mysql:host=$host;dbname=resto_prismatic;charset=utf8", $username, $password);
        $stmt = $pdo_resto->query("SHOW TABLES LIKE 'menu'");
        $menu_exists = $stmt->rowCount() > 0;
        
        $stmt = $pdo_resto->query("SHOW TABLES LIKE 'orders'");
        $orders_exists = $stmt->rowCount() > 0;
        
        if ($menu_exists && $orders_exists) {
            echo "✅ <strong>Database 'resto_prismatic' sudah siap!</strong><br>";
            echo "🔗 <a href='http://localhost:5176/menu' target='_blank'>Test Menu Page</a><br>";
            echo "🔗 <a href='http://localhost:5176/orders' target='_blank'>Test Orders Page</a><br>";
            echo "🔗 <a href='api/get_menu.php' target='_blank'>Test API Menu</a><br>";
            echo "🔗 <a href='api/get_orders.php' target='_blank'>Test API Orders</a><br>";
        } else {
            echo "⚠️ Database 'resto_prismatic' ada tapi struktur belum lengkap<br>";
            echo "🔧 <a href='create_database.php' target='_blank'>Jalankan Setup Database</a><br>";
        }
        
    } catch (PDOException $e) {
        echo "❌ Database 'resto_prismatic' belum ada<br>";
        echo "🔧 <a href='create_database.php' target='_blank'>Jalankan Setup Database</a><br>";
    }
    
    // Check if apirestoran exists
    try {
        $pdo_api = new PDO("mysql:host=$host;dbname=apirestoran;charset=utf8", $username, $password);
        $stmt = $pdo_api->query("SHOW TABLES LIKE 'menus'");
        $menus_exists = $stmt->rowCount() > 0;
        
        if ($menus_exists) {
            echo "<br>ℹ️ Database 'apirestoran' juga ditemukan dengan tabel 'menus'<br>";
            echo "💡 Anda bisa menggunakan data dari 'apirestoran' atau tetap pakai 'resto_prismatic'<br>";
        }
        
    } catch (PDOException $e) {
        // apirestoran doesn't exist, that's fine
    }
    
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database Connection</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP sudah running</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Username 'root' dan password kosong benar</li>";
    echo "</ul>";
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

table {
    background-color: white;
    color: black;
    width: 100%;
    margin-bottom: 15px;
}

th {
    background-color: #007bff !important;
    color: white !important;
}

tr:nth-child(even) {
    background-color: #f8f9fa;
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
</style>
