<?php
session_start();
require_once 'config/database.php';

// Cek login admin
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar produk
$produk_query = mysqli_query($koneksi, "SELECT * FROM produk");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Manajemen Produk</h2>
        
        <a href="add_product.php" class="btn btn-success mb-3">Tambah Produk Baru</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($produk = mysqli_fetch_assoc($produk_query)): ?>
                <tr>
                    <td><img src="<?= $produk['gambar'] ?>" width="100"></td>
                    <td><?= $produk['nama'] ?></td>
                    <td>Rp. <?= number_format($produk['harga']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $produk['id'] ?>" class="btn btn-warning">Edit</a>
                        <form action="delete_product.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $produk['id'] ?>">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>