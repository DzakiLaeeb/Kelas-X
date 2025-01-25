<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 2rem;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            padding: 0.75rem 1.5rem;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">TokoOnline</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="shop.php">Belanja</a></li>
                        <li class="nav-item"><a class="nav-link" href="tentangkami.php">Tentang Kami</a></li>
                        <li class="nav-item"><a class="nav-link active" href="hubungikami.php">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>Hubungi Kami</h2>
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" placeholder="Masukkan nama Anda">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Pesan</label>
                <textarea class="form-control" id="message" rows="4" placeholder="Tulis pesan Anda"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>

    <footer class="text-center mt-5 py-4 bg-dark text-light">
        <p>&copy; 2025 TokoOnline. Hak Cipta Dilindungi.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
