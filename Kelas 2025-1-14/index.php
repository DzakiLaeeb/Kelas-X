<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
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

        /* Search Bar */
        .search-container {
            display: flex;
            gap: 1rem;
            margin: 1rem auto;
            max-width: 600px;
            padding: 0 20px;
        }

        .search-input {
            flex: 1;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .search-button {
            padding: 0.8rem 1.5rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #2980b9;
        }

        /* Banner Section */
        .banner {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .banner img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Products Section */
        .products-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .product-price {
            color: #27ae60;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 0 20px;
        }

        .footer-section h4 {
            color: #3498db;
            margin-bottom: 1.5rem;
        }

        .footer-menu ul {
            list-style: none;
        }

        .footer-menu ul li {
            margin-bottom: 0.8rem;
        }

        .footer-menu ul li a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-menu ul li a:hover {
            color: #3498db;
        }

        .social-media {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background-color: #34495e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .social-icon:hover {
            background-color: #3498db;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #34495e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Banner Section */
        .banner {
            max-width: 700px;
            max-height: 295px;
            margin-left: 55px;
            overflow: hidden;
            position: relative;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .banner-slider {
            display: flex;
            width: 300%;
            animation: slide 9s infinite linear;
        }

        .banner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        @keyframes slide {
            0% { transform: translateX(0); }
            33% { transform: translateX(-33.33%); }
            66% { transform: translateX(-66.66%); }
            100% { transform: translateX(0); }
        }

        .containerbanner {
            display: flex;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <div class="logo">TokoOnline</div>
                <div class="nav-menu">
                    <a href="#">Beranda</a>
                    <a href="shop.php">Belanja</a>
                    <a href="tentangkami.php">Tentang Kami</a>
                    <a href="hubungikami.php">Hubungi Kami</a>
                    <a href="login.php">Masuk</a>
                    <a href="register.php">Daftar</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="search-container">
        <input type="text" class="search-input" placeholder="Cari produk...">
        <button class="search-button">Cari</button>
    </div>

    <div class="containerbanner" style="gap: 25px">
            
        <section class="banner">
            <div class="banner-slider">
                <img src="images/4.jpg" alt="Banner 1">
                <img src="images/5.png" alt="Banner 2">
                <img src="images/6.jpeg" alt="Banner 3">
            </div>
        </section>
        
        <section class="banner2" style="margin-top: 33px; gap: 25px">
                <img style="width: 455px; height: 143px; border-radius: 15px" src="images/4.jpg" alt="Banner 1">
                <img style="width: 455px; height: 143px; border-radius: 15px" src="images/4.jpg" alt="Banner 1">
        </section>

    </div>

    <section class="products-container">
        <div class="products-grid">
            <div class="product-card">
                <img src="images/1.png" alt="Produk 1" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Nama Produk 1</h3>
                    <p class="product-price">Rp 150.000</p>
                </div>
            </div>

            <div class="product-card">
                <img src="images/2.png" alt="Produk 2" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Nama Produk 2</h3>
                    <p class="product-price">Rp 200.000</p>
                </div>
            </div>

            <div class="product-card">
                <img src="images/3.png" alt="Produk 3" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Nama Produk 3</h3>
                    <p class="product-price">Rp 175.000</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Menu</h4>
                <div class="footer-menu">
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Produk</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-section">
                <h4>Metode Pembayaran</h4>
                <img src="/api/placeholder/200/60" alt="Metode Pembayaran">
            </div>

            <div class="footer-section">
                <h4>Media Sosial</h4>
                <div class="social-media">
                    <a href="#" class="social-icon">FB</a>
                    <a href="#" class="social-icon">IG</a>
                    <a href="#" class="social-icon">TW</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Kontak Kami</h4>
                <p>Email: info@tokonline.com</p>
                <p>Telepon: (021) 1234-5678</p>
                <p>Alamat: Jalan Contoh No. 123, Jakarta</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 TokoOnline. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
</body>
</html>