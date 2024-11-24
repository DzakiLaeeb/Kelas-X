<?php 

if (!isset($_SESSION["email"])) {
    header("location:index.php?menu=login");
    exit();
}

// Menambahkan produk ke keranjang
if (isset($_GET["add"])) {
    $id = $_GET["add"];
    $sql = "SELECT * FROM produk WHERE id = $id";
    $hasil = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($hasil);

    if ($row) {
        // Jika keranjang belum ada, buat keranjang
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id]["jumlah"] += 1;
        } else {
            // Jika produk belum ada, tambahkan ke keranjang
            $_SESSION["cart"][$id] = [
                "id" => $row["id"],
                "produk" => $row["produk"],
                "harga" => $row["harga"],
                "jumlah" => 1
            ];
        }
    }
}

// Menghapus produk dari keranjang
if (isset($_GET["hapus"])) {
    $hapus = $_GET["hapus"];
    if (isset($_SESSION["cart"][$hapus])) {
        unset($_SESSION["cart"][$hapus]);
    }
}
?>

<h1>Cart</h1>
<div class="cart">
    <h1>Keranjang</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Hapus</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $no = 1;
        $totalsemua = 0;

        if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
            foreach ($_SESSION["cart"] as $key) {
                $total = $key["jumlah"] * $key["harga"];
                $totalsemua += $total;
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $key["produk"] ?></td>
                <td><?= $key["harga"] ?></td>
                <td><?= $key["jumlah"] ?></td>
                <td><?= $total ?></td>
                <td><a href="?menu=cart&hapus=<?= $key["id"] ?>">Hapus</a></td>
            </tr>
        <?php 
            }
        } else {
        ?>
            <tr>
                <td colspan="6">Keranjang kosong</td>
            </tr>
        <?php 
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total semua</td>
                <td colspan="2"><?= $totalsemua ?></td>
            </tr>
        </tfoot>
    </table>
</div>
