<?php
/**
 * Setup Database untuk Fitur Orders
 * Jalankan file ini sekali untuk membuat tabel orders
 */

// Database connection
$host = 'localhost';
$dbname = 'resto_prismatic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🚀 Setup Database untuk Fitur Orders</h2>";
    echo "<hr>";
    
    // Create orders table
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            menu_id INT NOT NULL,
            nama_menu VARCHAR(255) NOT NULL,
            harga_satuan DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            total_harga DECIMAL(10,2) NOT NULL,
            customer_name VARCHAR(255) NOT NULL DEFAULT 'Guest User',
            customer_phone VARCHAR(20) DEFAULT '',
            notes TEXT DEFAULT '',
            status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_menu_id (menu_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_customer_name (customer_name)
        )
    ";
    
    $pdo->exec($createTableSQL);
    echo "✅ <strong>Tabel 'orders' berhasil dibuat!</strong><br><br>";
    
    // Check if table has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        echo "📝 <strong>Menambahkan data sample...</strong><br>";
        
        // Insert sample data
        $sampleData = [
            [1, 'Nasi Goreng Spesial', 25000.00, 2, 50000.00, 'John Doe', '081234567890', 'Pedas sedang', 'pending'],
            [2, 'Ayam Bakar', 30000.00, 1, 30000.00, 'Jane Smith', '081234567891', 'Tanpa sambal', 'confirmed'],
            [3, 'Es Teh Manis', 8000.00, 3, 24000.00, 'Bob Wilson', '081234567892', '', 'ready']
        ];
        
        $insertSQL = "
            INSERT INTO orders (menu_id, nama_menu, harga_satuan, quantity, total_harga, customer_name, customer_phone, notes, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $stmt = $pdo->prepare($insertSQL);
        
        foreach ($sampleData as $data) {
            $stmt->execute($data);
        }
        
        echo "✅ <strong>Data sample berhasil ditambahkan!</strong><br><br>";
    } else {
        echo "ℹ️ <strong>Tabel sudah memiliki $count data</strong><br><br>";
    }
    
    // Show table structure
    echo "<h3>📋 Struktur Tabel Orders:</h3>";
    $stmt = $pdo->query("DESCRIBE orders");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td><strong>{$column['Field']}</strong></td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show sample data
    echo "<h3>📊 Data Sample:</h3>";
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($orders)) {
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Menu</th><th>Qty</th><th>Total</th><th>Customer</th><th>Status</th><th>Created</th>";
        echo "</tr>";
        
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>{$order['id']}</td>";
            echo "<td>{$order['nama_menu']}</td>";
            echo "<td>{$order['quantity']}</td>";
            echo "<td>Rp " . number_format($order['total_harga'], 0, ',', '.') . "</td>";
            echo "<td>{$order['customer_name']}</td>";
            echo "<td><span style='padding: 4px 8px; background-color: #007bff; color: white; border-radius: 4px; font-size: 12px;'>{$order['status']}</span></td>";
            echo "<td>{$order['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h3>🎉 Setup Database Selesai!</h3>";
    echo "<p><strong>Langkah selanjutnya:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Tabel orders sudah siap</li>";
    echo "<li>✅ API endpoints sudah tersedia</li>";
    echo "<li>✅ Frontend sudah terintegrasi</li>";
    echo "<li>🔗 Buka <a href='http://localhost:5176/menu' target='_blank'>halaman Menu</a> untuk test tombol beli</li>";
    echo "<li>🔗 Buka <a href='http://localhost:5176/orders' target='_blank'>halaman Orders</a> untuk melihat daftar pesanan</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database Connection</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP sudah running</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Database 'resto_prismatic' sudah ada</li>";
    echo "<li>Username dan password database benar</li>";
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
    background-color: #f5f5f5;
}

h2, h3 {
    color: #333;
}

table {
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

th {
    background-color: #007bff !important;
    color: white !important;
}

tr:nth-child(even) {
    background-color: #f8f9fa;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
