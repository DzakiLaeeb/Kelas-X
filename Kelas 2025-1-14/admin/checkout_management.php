<?php
session_start();
require_once 'config/database.php';

// Cek login admin
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar pesanan
$pesanan_query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Daftar Pesanan Checkout</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($pesanan = mysqli_fetch_assoc($pesanan_query)): ?>
                <tr>
                    <td><?= $pesanan['id'] ?></td>
                    <td><?= $pesanan['nama_pelanggan'] ?></td>
                    <td>Rp. <?= number_format($pesanan['total_harga']) ?></td>
                    <td><?= $pesanan['status_pengiriman'] ?></td>
                    <td>
                        <a href="detail_pesanan.php?id=<?= $pesanan['id'] ?>" class="btn btn-info">Detail</a>
                        <form action="update_pengiriman.php" method="POST" style="display:inline;">
                            <input type="hidden" name="pesanan_id" value="<?= $pesanan['id'] ?>">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="Diproses" <?= $pesanan['status_pengiriman'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Dikirim" <?= $pesanan['status_pengiriman'] == 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="Selesai" <?= $pesanan['status_pengiriman'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>