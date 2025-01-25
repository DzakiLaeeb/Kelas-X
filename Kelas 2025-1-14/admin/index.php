<?php
session_start();
require_once 'config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="banner_management.php">Manajemen Banner</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product_management.php">Manajemen Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="checkout_management.php">Pesanan Checkout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Konten Utama -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <h1>Selamat Datang di Dashboard Admin</h1>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Produk</h5>
                                <?php
                                $produk_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
                                $produk_count = mysqli_fetch_assoc($produk_query)['total'];
                                echo "<p class='card-text'>$produk_count Produk</p>";
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pesanan</h5>
                                <?php
                                $pesanan_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan");
                                $pesanan_count = mysqli_fetch_assoc($pesanan_query)['total'];
                                echo "<p class='card-text'>$pesanan_count Pesanan</p>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>