<?php
/**
 * Quick Add Menu - Simple Script
 * Script sederhana untuk menambah menu ke database apirestoran
 */

$host = 'localhost';
$dbname = 'apirestoran';
$username = 'root';
$password = '';

echo "<h2>🚀 Quick Add Menu to apirestoran</h2>";
echo "<hr>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!<br><br>";
    
    // Check current count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menus");
    $current_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "📊 Current menu count: $current_count<br><br>";
    
    // Simple menu data
    $menus = [
        ['Nasi Goreng Spesial', 'Nasi goreng dengan telur dan ayam', 25000, 1, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400'],
        ['Ayam Bakar', 'Ayam bakar bumbu kecap', 30000, 1, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=400'],
        ['Mie Ayam', 'Mie ayam dengan bakso', 20000, 1, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400'],
        ['Es Teh Manis', 'Teh manis dingin', 8000, 2, 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?w=400'],
        ['Jus Jeruk', 'Jus jeruk segar', 12000, 2, 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=400'],
        ['Keripik Singkong', 'Keripik singkong pedas', 15000, 3, 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=400'],
        ['Es Krim', 'Es krim vanilla', 18000, 4, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400']
    ];
    
    echo "📝 Adding " . count($menus) . " menus...<br>";
    
    foreach ($menus as $menu) {
        $sql = "INSERT INTO menus (menu, deskripsi, harga, kategori_id, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute($menu);
            echo "✅ Added: {$menu[0]}<br>";
        } catch (PDOException $e) {
            echo "❌ Error adding {$menu[0]}: " . $e->getMessage() . "<br>";
        }
    }
    
    // Check final count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM menus");
    $final_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "<br><hr>";
    echo "<h3>🎉 Done!</h3>";
    echo "📊 Final menu count: <strong>$final_count</strong><br><br>";
    
    echo "<h4>🔗 Test Links:</h4>";
    echo "<ul>";
    echo "<li><a href='http://localhost:5176/menu' target='_blank'>🍽️ Menu Page</a></li>";
    echo "<li><a href='api/get_menu.php' target='_blank'>📡 API Get Menu</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
    echo "Make sure XAMPP is running and database 'apirestoran' exists.";
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px; 
    background: #f5f5f5; 
}
h2, h3 { color: #333; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
