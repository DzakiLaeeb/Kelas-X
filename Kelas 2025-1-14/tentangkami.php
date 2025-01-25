<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online - Tentang Kami</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Import fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Header Styles */
        header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
        }

        .nav-menu a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: #3498db;
        }

        .nav-link {
            font-weight: 500;
            color: #2c3e50 !important;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #3498db !important;
        }

        .hero-section {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 6rem 0;
            margin-bottom: 4rem;
        }

        .hero-text {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .mission-vision {
            padding: 4rem 0;
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-icon {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1.5rem;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .team-section {
            padding: 4rem 0;
        }

        .team-member {
            text-align: center;
            margin-bottom: 2rem;
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }

        .stats-section {
            background-color: #3498db;
            color: white;
            padding: 4rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 3rem 0;
        }

        .contact-info {
            margin-bottom: 1.5rem;
        }

        .social-links a {
            color: white;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #3498db;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <div class="logo">TokoOnline</div>
                <div class="nav-menu">
                    <a href="index.php">Beranda</a>
                    <a href="shop.php">Belanja</a>
                    <a href="tentangkami.php">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Masuk</a>
                    <a href="#">Daftar</a>
                </div>
            </div>
        </nav>
    </header>

    <section class="hero-section text-center">
        <div class="container">
            <div class="hero-text">
                <h1 class="hero-title">Tentang Kami</h1>
                <p class="lead">Selamat datang di TokoOnline, destinasi terbaik untuk produk berkualitas dengan harga terjangkau. Kami berkomitmen untuk memberikan pengalaman belanja online terbaik untuk Anda.</p>
            </div>
        </div>
    </section>

    <section class="mission-vision">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100 p-4">
                        <div class="card-icon">🎯</div>
                        <h3 class="card-title">Misi Kami</h3>
                        <p>Kami berkomitmen untuk memberikan pengalaman belanja yang nyaman dan aman bagi pelanggan kami. Dengan fokus pada kualitas produk dan layanan pelanggan yang prima.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-4">
                        <div class="card-icon">🚀</div>
                        <h3 class="card-title">Visi Kami</h3>
                        <p>Menjadi platform e-commerce terdepan yang dipercaya oleh masyarakat luas, dengan inovasi teknologi dan pelayanan yang terbaik di kelasnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container text-center">
            <h2 class="mb-5">Tim Kami</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="/api/placeholder/150/150" alt="Team Member 1">
                        <h4>John Doe</h4>
                        <p>CEO & Founder</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="/api/placeholder/150/150" alt="Team Member 2">
                        <h4>Jane Smith</h4>
                        <p>Head of Operations</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-member">
                        <img src="/api/placeholder/150/150" alt="Team Member 3">
                        <h4>Mike Johnson</h4>
                        <p>Customer Service Lead</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">10k+</div>
                        <div>Pelanggan Puas</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">5000+</div>
                        <div>Produk</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99%</div>
                        <div>Rating Positif</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div>Dukungan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center">
            <div class="contact-info">
                <p>Email: info@tokonline.com | Telepon: (021) 1234-5678</p>
                <p>Alamat: Jalan Contoh No. 123, Jakarta</p>
            </div>
            <div class="social-links">
                <a href="#"><span>📱</span></a>
                <a href="#"><span>📸</span></a>
                <a href="#"><span>🐦</span></a>
            </div>
            <p class="mt-4">&copy; 2025 TokoOnline. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>