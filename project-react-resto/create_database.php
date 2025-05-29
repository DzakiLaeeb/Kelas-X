<?php
/**
 * Create Database dan Setup Lengkap untuk Resto Prismatic
 * Jalankan file ini untuk membuat database dan semua tabel yang diperlukan
 */

// Database connection tanpa database name dulu
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'resto_prismatic';

try {
    // Connect tanpa database name untuk create database
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🚀 Setup Database Resto Prismatic</h2>";
    echo "<hr>";
    
    // Create database
    echo "📝 <strong>Step 1: Membuat Database...</strong><br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "✅ Database '$dbname' berhasil dibuat!<br><br>";
    
    // Connect to the new database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create kategori table
    echo "📝 <strong>Step 2: Membuat Tabel Kategori...</strong><br>";
    $createKategoriSQL = "
        CREATE TABLE IF NOT EXISTS kategori (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(255) NOT NULL,
            deskripsi TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($createKategoriSQL);
    echo "✅ Tabel 'kategori' berhasil dibuat!<br>";
    
    // Insert sample kategori
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM kategori");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        $kategoriData = [
            ['Makanan Utama', 'Nasi, mie, dan makanan berat lainnya'],
            ['Minuman', 'Minuman segar dan hangat'],
            ['Snack', 'Camilan dan makanan ringan'],
            ['Dessert', 'Makanan penutup dan es krim']
        ];
        
        $insertKategoriSQL = "INSERT INTO kategori (nama, deskripsi) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertKategoriSQL);
        
        foreach ($kategoriData as $data) {
            $stmt->execute($data);
        }
        echo "✅ Data sample kategori berhasil ditambahkan!<br>";
    }
    echo "<br>";
    
    // Create menu table
    echo "📝 <strong>Step 3: Membuat Tabel Menu...</strong><br>";
    $createMenuSQL = "
        CREATE TABLE IF NOT EXISTS menu (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(255) NOT NULL,
            deskripsi TEXT,
            harga DECIMAL(10,2) NOT NULL,
            kategori_id INT,
            image VARCHAR(500),
            rating DECIMAL(3,2) DEFAULT 5.00,
            is_available BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL,
            INDEX idx_kategori_id (kategori_id),
            INDEX idx_is_available (is_available)
        )
    ";
    $pdo->exec($createMenuSQL);
    echo "✅ Tabel 'menu' berhasil dibuat!<br>";
    
    // Insert sample menu
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        $menuData = [
            ['Nasi Goreng Spesial', 'Nasi goreng dengan telur, ayam, udang, dan sayuran segar', 25000.00, 1, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&w=400', 4.8],
            ['Ayam Bakar Madu', 'Ayam bakar dengan bumbu madu dan rempah pilihan', 30000.00, 1, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?ixlib=rb-4.0.3&w=400', 4.7],
            ['Mie Ayam Bakso', 'Mie ayam dengan bakso sapi dan pangsit goreng', 20000.00, 1, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&w=400', 4.6],
            ['Sate Ayam', 'Sate ayam dengan bumbu kacang khas Indonesia', 35000.00, 1, 'https://images.unsplash.com/photo-1529563021893-cc83c992d75d?ixlib=rb-4.0.3&w=400', 4.9],
            ['Es Teh Manis', 'Teh manis segar dengan es batu', 8000.00, 2, 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?ixlib=rb-4.0.3&w=400', 4.5],
            ['Jus Jeruk', 'Jus jeruk segar tanpa gula tambahan', 12000.00, 2, 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?ixlib=rb-4.0.3&w=400', 4.4],
            ['Kopi Hitam', 'Kopi hitam robusta pilihan', 10000.00, 2, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&w=400', 4.3],
            ['Keripik Singkong', 'Keripik singkong renyah dengan bumbu balado', 15000.00, 3, 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?ixlib=rb-4.0.3&w=400', 4.2],
            ['Es Krim Vanilla', 'Es krim vanilla dengan topping coklat', 18000.00, 4, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&w=400', 4.6],
            ['Pudding Coklat', 'Pudding coklat lembut dengan saus karamel', 16000.00, 4, 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&w=400', 4.5]
        ];
        
        $insertMenuSQL = "INSERT INTO menu (nama, deskripsi, harga, kategori_id, image, rating) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertMenuSQL);
        
        foreach ($menuData as $data) {
            $stmt->execute($data);
        }
        echo "✅ Data sample menu berhasil ditambahkan!<br>";
    }
    echo "<br>";
    
    // Create orders table
    echo "📝 <strong>Step 4: Membuat Tabel Orders...</strong><br>";
    $createOrdersSQL = "
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
            
            FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE,
            INDEX idx_menu_id (menu_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_customer_name (customer_name)
        )
    ";
    $pdo->exec($createOrdersSQL);
    echo "✅ Tabel 'orders' berhasil dibuat!<br>";
    
    // Insert sample orders
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        $ordersData = [
            [1, 'Nasi Goreng Spesial', 25000.00, 2, 50000.00, 'John Doe', '081234567890', 'Pedas sedang', 'pending'],
            [2, 'Ayam Bakar Madu', 30000.00, 1, 30000.00, 'Jane Smith', '081234567891', 'Tanpa sambal', 'confirmed'],
            [5, 'Es Teh Manis', 8000.00, 3, 24000.00, 'Bob Wilson', '081234567892', '', 'ready'],
            [4, 'Sate Ayam', 35000.00, 1, 35000.00, 'Alice Brown', '081234567893', 'Bumbu kacang extra', 'preparing']
        ];
        
        $insertOrdersSQL = "INSERT INTO orders (menu_id, nama_menu, harga_satuan, quantity, total_harga, customer_name, customer_phone, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertOrdersSQL);
        
        foreach ($ordersData as $data) {
            $stmt->execute($data);
        }
        echo "✅ Data sample orders berhasil ditambahkan!<br>";
    }
    echo "<br>";
    
    // Show summary
    echo "<hr>";
    echo "<h3>📊 Summary Database:</h3>";
    
    $tables = ['kategori', 'menu', 'orders'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "✅ Tabel <strong>$table</strong>: $count records<br>";
    }
    
    echo "<br><hr>";
    echo "<h3>🎉 Setup Database Selesai!</h3>";
    echo "<p><strong>Database 'resto_prismatic' sudah siap digunakan!</strong></p>";
    echo "<ul>";
    echo "<li>✅ Database resto_prismatic dibuat</li>";
    echo "<li>✅ Tabel kategori, menu, orders dibuat</li>";
    echo "<li>✅ Data sample sudah dimasukkan</li>";
    echo "<li>✅ Foreign keys dan indexes sudah diatur</li>";
    echo "</ul>";
    
    echo "<h4>🔗 Test Links:</h4>";
    echo "<ul>";
    echo "<li><a href='http://localhost:5176/menu' target='_blank'>🍽️ Halaman Menu</a> - Test tombol beli</li>";
    echo "<li><a href='http://localhost:5176/orders' target='_blank'>🛒 Halaman Orders</a> - Lihat daftar pesanan</li>";
    echo "<li><a href='api/get_menu.php' target='_blank'>📡 API Get Menu</a> - Test API menu</li>";
    echo "<li><a href='api/get_orders.php' target='_blank'>📡 API Get Orders</a> - Test API orders</li>";
    echo "<li><a href='test_api.html' target='_blank'>🧪 Test API</a> - Test semua API</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP sudah running</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Username 'root' dan password kosong benar</li>";
    echo "<li>Port MySQL default (3306) tidak diblokir</li>";
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
}

h2, h3 {
    color: white;
}

h2 {
    text-align: center;
    margin-bottom: 30px;
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
    color: white;
}

hr {
    border: none;
    height: 2px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    margin: 30px 0;
}

strong {
    color: white;
}

p {
    color: rgba(255, 255, 255, 0.9);
}
</style>
