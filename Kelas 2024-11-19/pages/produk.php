<?php
if (!isset($_SESSION["email"])) {
    header("location:index.php?menu=login");
    exit();
}

$sql = "SELECT * FROM produk ORDER BY produk ASC";
$hasil = mysqli_query($koneksi, $sql);

$baris = mysqli_num_rows($hasil);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Produk</h1>
    <?php 
    if ($baris == 0) {
        echo "<h2>Produk belum diisi</h2>";
    } else {
        while ($row = mysqli_fetch_assoc($hasil)) {
    ?>
    <div class="produk">
        <div class="detail-produk">
            <h2><?php echo $row["produk"]; ?></h2>
            <img style="width: 200px; height: 200px;" 
                 src="images/<?php echo $row['gambar']; ?>" alt="">
            <p><?php echo $row["deskripsi"]; ?></p>
            <p>Stok: <?php echo (int)$row["stok"]; ?></p>
            <p><strong>Harga: Rp <?php echo $row["harga"]; ?></strong></p>
            <a href="?menu=cart&add=<?php echo $row['id']; ?>"><button>Beli</button></a>
        </div>
    </div>
    <?php 
        }
    }
    ?>
</body>
</html>
