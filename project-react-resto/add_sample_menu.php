<?php
/**
 * Add Sample Menu Data
 * Script untuk menambahkan data menu sample yang menarik
 */

// Database connection
$host = 'localhost';
$dbname = 'resto_prismatic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🍽️ Menambahkan Data Menu Sample</h2>";
    echo "<hr>";
    
    // Check current menu count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu");
    $current_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "📊 <strong>Menu saat ini:</strong> $current_count items<br><br>";
    
    // Clear existing menu data (optional)
    if (isset($_GET['clear']) && $_GET['clear'] == 'yes') {
        echo "🗑️ <strong>Menghapus data menu lama...</strong><br>";
        $pdo->exec("DELETE FROM menu");
        $pdo->exec("ALTER TABLE menu AUTO_INCREMENT = 1");
        echo "✅ Data menu lama berhasil dihapus!<br><br>";
    }
    
    // Sample menu data yang menarik
    $menuData = [
        // Makanan Utama (kategori_id = 1)
        ['Nasi Goreng Spesial', 'Nasi goreng dengan telur, ayam, udang, dan sayuran segar. Disajikan dengan kerupuk dan acar.', 25000.00, 1, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&w=400', 4.8],
        ['Ayam Bakar Madu', 'Ayam bakar dengan bumbu madu dan rempah pilihan. Disajikan dengan nasi putih dan lalapan.', 30000.00, 1, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?ixlib=rb-4.0.3&w=400', 4.7],
        ['Mie Ayam Bakso', 'Mie ayam dengan bakso sapi dan pangsit goreng. Kuah kaldu segar dan bumbu kacang.', 20000.00, 1, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&w=400', 4.6],
        ['Sate Ayam', 'Sate ayam dengan bumbu kacang khas Indonesia. 10 tusuk dengan lontong dan timun.', 35000.00, 1, 'https://images.unsplash.com/photo-1529563021893-cc83c992d75d?ixlib=rb-4.0.3&w=400', 4.9],
        ['Rendang Daging', 'Rendang daging sapi empuk dengan bumbu rempah tradisional. Disajikan dengan nasi putih.', 40000.00, 1, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&w=400', 4.8],
        ['Gado-Gado Jakarta', 'Gado-gado dengan sayuran segar, tahu, tempe, dan bumbu kacang spesial.', 18000.00, 1, 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?ixlib=rb-4.0.3&w=400', 4.5],
        
        // Minuman (kategori_id = 2)
        ['Es Teh Manis', 'Teh manis segar dengan es batu. Minuman tradisional yang menyegarkan.', 8000.00, 2, 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?ixlib=rb-4.0.3&w=400', 4.5],
        ['Jus Jeruk Segar', 'Jus jeruk segar tanpa gula tambahan. Kaya vitamin C dan menyegarkan.', 12000.00, 2, 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?ixlib=rb-4.0.3&w=400', 4.4],
        ['Kopi Hitam Robusta', 'Kopi hitam robusta pilihan dengan aroma yang kuat. Cocok untuk pecinta kopi.', 10000.00, 2, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&w=400', 4.3],
        ['Es Kelapa Muda', 'Es kelapa muda segar dengan daging kelapa yang lembut. Sangat menyegarkan.', 15000.00, 2, 'https://images.unsplash.com/photo-1585238342024-78d387f4a707?ixlib=rb-4.0.3&w=400', 4.6],
        ['Cappuccino', 'Cappuccino dengan foam susu yang creamy. Disajikan hangat dengan taburan coklat.', 18000.00, 2, 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-4.0.3&w=400', 4.7],
        
        // Snack (kategori_id = 3)
        ['Keripik Singkong', 'Keripik singkong renyah dengan bumbu balado pedas. Camilan tradisional yang gurih.', 15000.00, 3, 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?ixlib=rb-4.0.3&w=400', 4.2],
        ['Pisang Goreng', 'Pisang goreng crispy dengan taburan gula halus. Hangat dan manis.', 12000.00, 3, 'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?ixlib=rb-4.0.3&w=400', 4.4],
        ['Tahu Isi', 'Tahu isi dengan sayuran dan tauge. Digoreng crispy dan disajikan dengan saus kacang.', 10000.00, 3, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&w=400', 4.3],
        ['Bakwan Jagung', 'Bakwan jagung crispy dengan jagung manis. Camilan hangat yang gurih.', 8000.00, 3, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&w=400', 4.1],
        
        // Dessert (kategori_id = 4)
        ['Es Krim Vanilla', 'Es krim vanilla premium dengan topping coklat dan cherry. Dingin dan creamy.', 18000.00, 4, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&w=400', 4.6],
        ['Pudding Coklat', 'Pudding coklat lembut dengan saus karamel. Dessert yang manis dan lezat.', 16000.00, 4, 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&w=400', 4.5],
        ['Es Campur', 'Es campur dengan berbagai topping: cincau, kolang-kaling, dan sirup merah.', 14000.00, 4, 'https://images.unsplash.com/photo-1567206563064-6f60f40a2b57?ixlib=rb-4.0.3&w=400', 4.4],
        ['Klepon', 'Klepon tradisional dengan isian gula merah dan taburan kelapa parut.', 12000.00, 4, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&w=400', 4.3],
        ['Cendol Dawet', 'Cendol dawet dengan santan dan gula merah. Dessert tradisional yang menyegarkan.', 13000.00, 4, 'https://images.unsplash.com/photo-1567206563064-6f60f40a2b57?ixlib=rb-4.0.3&w=400', 4.5]
    ];
    
    echo "📝 <strong>Menambahkan " . count($menuData) . " menu baru...</strong><br>";
    
    $insertMenuSQL = "INSERT INTO menu (nama, deskripsi, harga, kategori_id, image, rating) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertMenuSQL);
    
    $success_count = 0;
    foreach ($menuData as $data) {
        try {
            $stmt->execute($data);
            $success_count++;
        } catch (PDOException $e) {
            echo "❌ Error adding menu '{$data[0]}': " . $e->getMessage() . "<br>";
        }
    }
    
    echo "✅ Berhasil menambahkan $success_count menu!<br><br>";
    
    // Show final count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menu");
    $final_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "<hr>";
    echo "<h3>📊 Summary:</h3>";
    echo "✅ Total menu sekarang: <strong>$final_count items</strong><br>";
    
    // Show menu by category
    $stmt = $pdo->query("
        SELECT k.nama as kategori, COUNT(m.id) as jumlah 
        FROM kategori k 
        LEFT JOIN menu m ON k.id = m.kategori_id 
        GROUP BY k.id, k.nama
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br><strong>Menu per kategori:</strong><br>";
    echo "<ul>";
    foreach ($categories as $cat) {
        echo "<li><strong>{$cat['kategori']}</strong>: {$cat['jumlah']} items</li>";
    }
    echo "</ul>";
    
    echo "<br><hr>";
    echo "<h3>🎉 Data Menu Berhasil Ditambahkan!</h3>";
    echo "<ul>";
    echo "<li>✅ $success_count menu baru telah ditambahkan</li>";
    echo "<li>✅ Semua kategori sudah memiliki menu</li>";
    echo "<li>✅ Gambar dari Unsplash sudah diatur</li>";
    echo "<li>✅ Rating dan harga sudah diatur</li>";
    echo "</ul>";
    
    echo "<h4>🔗 Test Links:</h4>";
    echo "<ul>";
    echo "<li><a href='http://localhost:5176/menu' target='_blank'>🍽️ Halaman Menu</a> - Lihat menu yang baru</li>";
    echo "<li><a href='api/get_menu.php' target='_blank'>📡 API Get Menu</a> - Test API menu</li>";
    echo "<li><a href='check_database.php' target='_blank'>🔍 Check Database</a> - Lihat struktur database</li>";
    echo "</ul>";
    
    if ($final_count < 10) {
        echo "<br><p><strong>💡 Tips:</strong> Jika ingin menghapus data lama dan mulai fresh, klik <a href='?clear=yes'>di sini</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Database</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Pastikan:</strong></p>";
    echo "<ul>";
    echo "<li>Database 'resto_prismatic' sudah ada</li>";
    echo "<li>Tabel 'menu' dan 'kategori' sudah dibuat</li>";
    echo "<li>Jalankan <a href='create_database.php'>create_database.php</a> terlebih dahulu</li>";
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
</style>
