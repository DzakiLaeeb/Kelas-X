<?php
require_once 'config.php';

try {
    $db = new PDO("mysql:host=localhost;dbname=restoran_db", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Define base URL for images using placeholder images
    $baseImageUrl = 'https://source.unsplash.com/featured/300x200?';
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

$menus = [
    // Makanan Utama
    [        'menu' => 'Nasi Goreng Spesial',
        'deskripsi' => 'Nasi goreng dengan telur, ayam, udang, dan sayuran',
        'harga' => 35000,
        'kategori_id' => 1,
        'gambar' => 'food/fried-rice'
    ],
    [
        'menu' => 'Mie Goreng Seafood',
        'deskripsi' => 'Mie goreng dengan campuran seafood segar',
        'harga' => 40000,
        'kategori_id' => 1,        'gambar' => 'food/fried-noodles'
    ],
    // Minuman
    [
        'menu' => 'Es Teh Manis',
        'deskripsi' => 'Teh manis dingin yang menyegarkan',
        'harga' => 8000,
        'kategori_id' => 2,
        'gambar' => 'drink/tea'
    ],
    [
        'menu' => 'Jus Alpukat',
        'deskripsi' => 'Jus alpukat segar dengan susu',
        'harga' => 15000,
        'kategori_id' => 2,
        'gambar' => 'drink/avocado-juice'
    ],
    // Dessert
    [
        'menu' => 'Pudding Coklat',
        'deskripsi' => 'Pudding coklat lembut dengan saus vanilla',
        'harga' => 20000,
        'kategori_id' => 3,
        'gambar' => 'dessert/chocolate-pudding'
    ],
    [
        'menu' => 'Es Krim Sundae',
        'deskripsi' => 'Es krim dengan topping buah dan coklat',
        'harga' => 25000,
        'kategori_id' => 3,
        'gambar' => 'dessert/ice-cream-sundae'
    ],
    // Snack
    [
        'menu' => 'Kentang Goreng',
        'deskripsi' => 'Kentang goreng crispy dengan saus',
        'harga' => 18000,
        'kategori_id' => 4,
        'gambar' => 'snack/french-fries'
    ],
    [
        'menu' => 'Pisang Goreng',
        'deskripsi' => 'Pisang goreng crispy dengan topping keju',
        'harga' => 15000,
        'kategori_id' => 4,
        'gambar' => 'snack/fried-banana'
    ]
];

try {
    foreach ($menus as $menu) {
        $query = "INSERT INTO menus (menu, deskripsi, gambar, harga, kategori_id, created_at, updated_at) 
                 VALUES (:menu, :deskripsi, :gambar, :harga, :kategori_id, NOW(), NOW())";
        
        $stmt = $db->prepare($query);        $stmt->execute([
            ':menu' => $menu['menu'],
            ':deskripsi' => $menu['deskripsi'],
            ':gambar' => $baseImageUrl . $menu['gambar'],  // Menambahkan base URL
            ':harga' => $menu['harga'],
            ':kategori_id' => $menu['kategori_id']
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Berhasil menambahkan ' . count($menus) . ' menu'
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}