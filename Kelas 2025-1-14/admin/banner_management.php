<?php
session_start();
require_once 'config/database.php';

// Cek login admin
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}

// Proses upload banner
if (isset($_POST['upload_banner'])) {
    $target_dir = "uploads/banners/";
    $target_file = $target_dir . basename($_FILES["banner_image"]["name"]);
    
    if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO banner (path) VALUES ('$target_file')";
        mysqli_query($koneksi, $query);
    }
}

// Ambil daftar banner
$banner_query = mysqli_query($koneksi, "SELECT * FROM banner");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Manajemen Banner</h2>
        
        <!-- Form Upload Banner -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="banner_image" required>
            <button type="submit" name="upload_banner" class="btn btn-primary">Upload Banner</button>
        </form>

        <!-- Daftar Banner -->
        <div class="row">
            <?php while($banner = mysqli_fetch_assoc($banner_query)): ?>
            <div class="col-md-4">
                <img src="<?= $banner['path'] ?>" class="img-fluid">
                <form action="hapus_banner.php" method="POST">
                    <input type="hidden" name="banner_id" value="<?= $banner['id'] ?>">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>