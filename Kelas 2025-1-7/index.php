<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dbcrud';

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'] ?? null;
    $harga = $_POST['harga'] ?? null;
    $stok = $_POST['stok'] ?? null;
    $agent = $_POST['agent'] ?? null;
    
    // Proses Upload Gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . uniqid() . basename($_FILES["gambar"]["name"]);
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $target_file;
        }
    }

    if (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
        // Hapus gambar lama
        $query_gambar = "SELECT gambar FROM produk WHERE id = $id";
        $result = mysqli_query($koneksi, $query_gambar);
        $row = mysqli_fetch_assoc($result);
        if (!empty($row['gambar']) && file_exists($row['gambar'])) {
            unlink($row['gambar']);
        }

        // Proses Hapus
        $query = "DELETE FROM produk WHERE id = $id";
        mysqli_query($koneksi, $query);
        exit;
    }

    if (empty($id)) {
        // Proses Tambah
        $query = "INSERT INTO produk (nama, harga, stok, gambar, agent) VALUES ('$nama', $harga, $stok, '$gambar', '$agent')";
    } else {
        // Proses Update
        if (!empty($gambar)) {
            // Hapus gambar lama jika ada gambar baru
            $query_gambar = "SELECT gambar FROM produk WHERE id = $id";
            $result = mysqli_query($koneksi, $query_gambar);
            $row = mysqli_fetch_assoc($result);
            if (!empty($row['gambar']) && file_exists($row['gambar'])) {
                unlink($row['gambar']);
            }
            
            $query = "UPDATE produk SET nama='$nama', harga=$harga, stok=$stok, gambar='$gambar', agent='$agent' WHERE id=$id";
        } else {
            $query = "UPDATE produk SET nama='$nama', harga=$harga, stok=$stok, agent='$agent' WHERE id=$id";
        }
    }
    
    mysqli_query($koneksi, $query);
    exit;
}

// Ambil data produk untuk edit
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['aksi']) && $_GET['aksi'] === 'ambil') {
    $id = $_GET['id'];
    $query = "SELECT * FROM produk WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    $produk = mysqli_fetch_assoc($result);
    
    header('Content-Type: application/json');
    echo json_encode($produk);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Valorant Item Store</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0f1923;
            color: #ff4655;
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #ff4655;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .produk {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .kartu-produk {
            background-color: #1f2933;
            border: 2px solid #ff4655;
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s;
        }
        .kartu-produk:hover {
            transform: scale(1.05);
        }
        .kartu-produk img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        form {
            background-color: #1f2933;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #0f1923;
            color: #ff4655;
            border: 1px solid #ff4655;
            border-radius: 5px;
        }
        button {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #ff4655;
            color: #0f1923;
        }
    </style>
</head>
<body>
    <h1>Valorant Item Store</h1>
    
    <div id="daftar-produk" class="produk">
        <?php
        $query = "SELECT * FROM produk";
        $result = mysqli_query($koneksi, $query);
        
        while($produk = mysqli_fetch_assoc($result)) {
            echo "<div class='kartu-produk'>";
            if (!empty($produk['gambar'])) {
                echo "<img src='{$produk['gambar']}' alt='{$produk['nama']}'>";
            }
            echo "<h3>{$produk['nama']}</h3>";
            echo "<p>Agent: {$produk['agent']}</p>";
            echo "<p>Harga: Rp " . number_format($produk['harga'], 2) . "</p>";
            echo "<p>Stok: {$produk['stok']}</p>";
            echo "<button onclick='hapusProduk({$produk['id']})'>Hapus</button>";
            echo "<button onclick='editProduk({$produk['id']})'>Edit</button>";
            echo "</div>";
        }
        ?>
    </div>

    <form id="form-produk" enctype="multipart/form-data">
        <input type="hidden" id="produk-id">
        <input type="text" id="nama" placeholder="Nama Produk" required>
        <select id="agent" required>
            <option value="">Pilih Agent</option>
            <option value="Jett">Jett</option>
            <option value="Phoenix">Phoenix</option>
            <option value="Sage">Sage</option>
            <option value="Sova">Sova</option>
            <option value="Raze">Raze</option>
            <option value="Cypher">Cypher</option>
            <option value="Reyna">Reyna</option>
            <option value="Killjoy">Killjoy</option>
            <option value="Breach">Breach</option>
        </select>
        <input type="number" id="harga" placeholder="Harga" required>
        <input type="number" id="stok" placeholder="Stok" required>
        <input type="file" id="gambar" accept="image/*">
        <button type="submit">Simpan</button>
    </form>

    <script>
        document.getElementById('form-produk').onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('id', document.getElementById('produk-id').value);
            formData.append('nama', document.getElementById('nama').value);
            formData.append('agent', document.getElementById('agent').value);
            formData.append('harga', document.getElementById('harga').value);
            formData.append('stok', document.getElementById('stok').value);
            
            const gambarFile = document.getElementById('gambar').files[0];
            if (gambarFile) {
                formData.append('gambar', gambarFile);
            }

            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });
            
            location.reload();
        };

        async function hapusProduk(id) {
            const formData = new FormData();
            formData.append('aksi', 'hapus');
            formData.append('id', id);

            await fetch('index.php', {
                method: 'POST',
                body: formData
            });
            
            location.reload();
        }

        async function editProduk(id) {
            const response = await fetch(`index.php?aksi=ambil&id=${id}`);
            const produk = await response.json();
            
            document.getElementById('produk-id').value = produk.id;
            document.getElementById('nama').value = produk.nama;
            document.getElementById('agent').value = produk.agent;
            document.getElementById('harga').value = produk.harga;
            document.getElementById('stok').value = produk.stok;
        }
    </script>
</body>
</html>