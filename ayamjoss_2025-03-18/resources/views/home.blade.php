@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Ayam Goreng <span class="highlight">Joss</span></h1>
        <p class="hero-subtitle">Renyah di Luar, Juicy di Dalam!</p>
        <div class="hero-buttons">
            <a href="{{ url('/menu') }}" class="btn-menu">Lihat Menu</a>
            <a href="{{ url('/menu') }}" class="btn-order">Pesan Sekarang</a>
        </div>
    </div>
    <div class="hero-image-container">
        <img src="https://th.bing.com/th/id/OIG3.3RA6jWisNKuU5JEyfzLY?pid=ImgGn" alt="Ayam Goreng Joss" class="hero-image">
        <div class="hero-badge">
            <span>Sejak 2010</span>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-drumstick-bite"></i>
                    </div>
                    <h3>Bahan Berkualitas</h3>
                    <p>Ayam segar pilihan dengan bumbu rahasia turun-temurun</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Pengiriman Cepat</h3>
                    <p>Diantar langsung ke lokasi Anda dalam 30 menit</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-smile-beam"></i>
                    </div>
                    <h3>Kepuasan Terjamin</h3>
                    <p>Rasakan kelezatan atau dapatkan uang kembali</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Menu Section -->
<div class="popular-menu-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Menu Favorit</h2>
            <p>Pilihan menu terlaris yang wajib Anda coba</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="menu-item">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1562967914-608f82629710?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=873&q=80" alt="Ayam Goreng Pedas">
                        <div class="menu-badge hot">Pedas</div>
                    </div>
                    <div class="menu-info">
                        <h3>Ayam Goreng Pedas</h3>
                        <div class="menu-meta">
                            <span class="menu-price">Rp 27.000</span>
                            <div class="menu-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span>(4.5)</span>
                            </div>
                        </div>
                        <a href="{{ url('/menu') }}" class="btn-add-to-cart">Pesan</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="menu-item">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Ayam Goreng Original">
                        <div class="menu-badge">Bestseller</div>
                    </div>
                    <div class="menu-info">
                        <h3>Ayam Goreng Original</h3>
                        <div class="menu-meta">
                            <span class="menu-price">Rp 25.000</span>
                            <div class="menu-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span>(5.0)</span>
                            </div>
                        </div>
                        <a href="{{ url('/menu') }}" class="btn-add-to-cart">Pesan</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="menu-item">
                    <div class="menu-image">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Ayam Bakar">
                        <div class="menu-badge new">Baru</div>
                    </div>
                    <div class="menu-info">
                        <h3>Ayam Bakar</h3>
                        <div class="menu-meta">
                            <span class="menu-price">Rp 28.000</span>
                            <div class="menu-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span>(4.0)</span>
                            </div>
                        </div>
                        <a href="{{ url('/menu') }}" class="btn-add-to-cart">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ url('/menu') }}" class="btn-view-all">Lihat Semua Menu</a>
        </div>
    </div>
</div>

<!-- Testimonial Section -->
<div class="testimonial-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Apa Kata Pelanggan</h2>
            <p>Testimoni dari pelanggan setia kami</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Ayam gorengnya benar-benar renyah di luar dan juicy di dalam. Bumbu rahasia mereka bikin ketagihan!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">
                            <img src="{{ asset('gambar/faaaa.png') }}" alt="faaaa">
                        </div>
                        <div class="testimonial-info">
                            <h4>faaaa</h4>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Saya sudah mencoba banyak ayam goreng, tapi Ayam Goreng Joss punya cita rasa yang berbeda. Pelayanannya juga cepat!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Siti Rahayu">
                        </div>
                        <div class="testimonial-info">
                            <h4>Siti Rahayu</h4>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Paket keluarga mereka sangat worth it! Porsinya banyak dan harganya terjangkau. Anak-anak saya selalu minta dibawakan pulang."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Agus Wijaya">
                        </div>
                        <div class="testimonial-info">
                            <h4>Agus Wijaya</h4>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Promo Section -->
<div class="promo-section">
    <div class="container">
        <div class="promo-content">
            <h2>Dapatkan Diskon 20%</h2>
            <p>Untuk pemesanan pertama melalui aplikasi kami</p>
            <div class="promo-buttons">
                <a href="#" class="btn-app">
                    <i class="fab fa-google-play"></i>
                    <span>Google Play</span>
                </a>
                <a href="#" class="btn-app">
                    <i class="fab fa-apple"></i>
                    <span>App Store</span>
                </a>
            </div>
        </div>
        <div class="promo-image">
            <img src="https://th.bing.com/th/id/OIG3.OjYPvbFIvtOLUK5N0SlR?pid=ImgGn" alt="Promo Ayam Goreng">
        </div>
    </div>
</div>

<style>
    /* Hero Section */
    .hero-section {
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #fff1e6 0%, #ffe8d6 100%);
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 50px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .hero-content {
        flex: 1;
        padding: 50px;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--dark-brown);
        margin-bottom: 15px;
        line-height: 1.2;
    }
    
    .hero-title .highlight {
        color: var(--primary-red);
        position: relative;
        display: inline-block;
    }
    
    .hero-title .highlight:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 8px;
        background-color: var(--primary-yellow);
        bottom: 5px;
        left: 0;
        z-index: -1;
        border-radius: 4px;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 30px;
    }
    
    .hero-buttons {
        display: flex;
        gap: 15px;
    }
    
    .btn-menu, .btn-order {
        padding: 12px 25px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-menu {
        background-color: transparent;
        color: var(--dark-brown);
        border: 2px solid var(--primary-yellow);
    }
    
    .btn-menu:hover {
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        transform: translateY(-3px);
    }
    
    .btn-order {
        background-color: var(--primary-red);
        color: white;
        border: 2px solid var(--primary-red);
    }
    
    .btn-order:hover {
        background-color: #d32f2f;
        border-color: #d32f2f;
        transform: translateY(-3px);
    }
    
    .hero-image-container {
        flex: 1;
        position: relative;
        height: 500px;
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-badge {
        position: absolute;
        top: 30px;
        right: 30px;
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: rotate(5deg);
    }
    
    /* Features Section */
    .features-section {
        padding: 50px 0;
        background-color: white;
        border-radius: 15px;
        margin-bottom: 50px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .feature-card {
        text-align: center;
        padding: 30px 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background-color: var(--primary-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: var(--dark-brown);
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        background-color: var(--primary-red);
        color: white;
        transform: rotate(10deg);
    }
    
    .feature-card h3 {
        font-weight: 600;
        color: var(--dark-brown);
        margin-bottom: 10px;
    }
    
    .feature-card p {
        color: #666;
    }
    
    /* Popular Menu Section */
    .popular-menu-section {
        padding: 50px 0;
        background-color: #f9f9f9;
        border-radius: 15px;
        margin-bottom: 50px;
    }
    
    .section-header {
        margin-bottom: 40px;
    }
    
    .section-header h2 {
        color: var(--primary-red);
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
    }
    
    .section-header h2:after {
        content: "";
        position: absolute;
        width: 60%;
        height: 3px;
        background-color: var(--primary-yellow);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }
    
    .section-header p {
        color: #666;
        font-size: 1.1rem;
        margin-top: 20px;
    }
    
    .menu-item {
        background-color: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .menu-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .menu-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .menu-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .menu-item:hover .menu-image img {
        transform: scale(1.1);
    }
    
    .menu-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .menu-badge.hot {
        background-color: var(--primary-red);
        color: white;
    }
    
    .menu-badge.new {
        background-color: #4CAF50;
        color: white;
    }
    
    .menu-info {
        padding: 20px;
    }
    
    .menu-info h3 {
        font-weight: 600;
        color: var(--dark-brown);
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    
    .menu-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .menu-price {
        font-weight: 700;
        color: var(--primary-red);
        font-size: 1.1rem;
    }
    
    .menu-rating {
        color: #FFD700;
        font-size: 0.9rem;
    }
    
    .menu-rating span {
        color: #666;
        margin-left: 5px;
    }
    
    .btn-add-to-cart {
        display: block;
        width: 100%;
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        text-align: center;
        padding: 10px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-add-to-cart:hover {
        background-color: var(--primary-red);
        color: white;
    }
    
    .btn-view-all {
        display: inline-block;
        background-color: transparent;
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-view-all:hover {
        background-color: var(--primary-red);
        color: white;
        transform: translateY(-3px);
    }
    
    /* Testimonial Section */
    .testimonial-section {
        padding: 50px 0;
        background-color: white;
        border-radius: 15px;
        margin-bottom: 50px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .testimonial-card {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: all 0.3s ease;
        border-top: 3px solid var(--primary-yellow);
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-content {
        margin-bottom: 20px;
        position: relative;
    }
    
    .testimonial-content:before {
        content: """;
        font-size: 5rem;
        color: var(--primary-yellow);
        opacity: 0.3;
        position: absolute;
        top: -40px;
        left: -15px;
        font-family: serif;
    }
    
    .testimonial-content p {
        color: #555;
        font-style: italic;
        position: relative;
        z-index: 1;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
    }
    
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
    }
    
    .testimonial-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .testimonial-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-brown);
        margin-bottom: 5px;
    }
    
    .testimonial-rating {
        color: #FFD700;
        font-size: 0.8rem;
    }
    
    /* Promo Section */
    .promo-section {
        background: linear-gradient(135deg, var(--primary-red) 0%, #ff5252 100%);
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 50px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .promo-section .container {
        display: flex;
        align-items: center;
    }
    
    .promo-content {
        flex: 1;
        padding: 50px 30px 50px 0;
        margin-left: 55px;
    }
    
    .promo-content h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .promo-content p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .promo-buttons {
        display: flex;
        gap: 15px;
    }
    
    .btn-app {
        display: inline-flex;
        align-items: center;
        background-color: white;
        color: var(--primary-red);
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-app i {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    
    .btn-app:hover {
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        transform: translateY(-3px);
    }
    
    .promo-image {
        flex: 1;
        height: 400px;
    }
    
    .promo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .hero-section {
            flex-direction: column;
        }
        
        .hero-content {
            padding: 30px;
            text-align: center;
        }
        
        .hero-buttons {
            justify-content: center;
        }
        
        .hero-image-container {
            width: 100%;
            height: 300px;
        }
        
        .promo-section .container {
            flex-direction: column;
        }
        
        .promo-content {
            padding: 30px;
            text-align: center;
        }
        
        .promo-buttons {
            justify-content: center;
        }
        
        .promo-image {
            width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .feature-card {
            margin-bottom: 30px;
        }
        
        .promo-content h2 {
            font-size: 2rem;
        }
        
        .promo-content p {
            font-size: 1rem;
        }
        
        .promo-buttons {
            flex-direction: column;
            gap: 10px;
        }
    }

    :root {
            --primary-red: #e63946;
            --primary-yellow: #ffb703;
            --secondary-yellow: #ffd166;
            --dark-brown: #5c3c10;
            --light-cream: #fff1e6;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-cream);
            transition: all 0.3s ease;
        }
        
        .navbar {
            background: var(--primary-red) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-yellow) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-yellow) !important;
            transform: translateY(-2px);
        }
        
        .navbar-toggler {
            border-color: var(--primary-yellow);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 183, 3, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .container.content-container {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .footer {
            background-color: var(--dark-brown);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            transition: all 0.3s ease;
        }
        
        .dropdown-menu {
            background-color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 8px 20px;
            color: var(--dark-brown);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-cream);
            color: var(--primary-red);
        }

        .dropdown-divider {
            border-color: var(--light-cream);
            margin: 8px 0;
        }

        .btn-order-now {
            background-color: var(--primary-yellow);
            color: var(--dark-brown);
            font-weight: 600;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-order-now:hover {
            background-color: var(--secondary-yellow);
            transform: scale(1.05);
        }
        
        /* Animated chicken icon for active page */
        .nav-item.active .nav-link::before {
            content: "🍗";
            margin-right: 5px;
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Enhanced Dark Mode Styling */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
            --light-cream: #1e1e1e;
            color-scheme: dark;
        }

        [data-bs-theme="dark"] .content-container {
            background-color: #1e1e1e;
            color: #e0e0e0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .navbar {
            background: #181818 !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .footer {
            background-color: #181818;
        }

        [data-bs-theme="dark"] .btn-order-now {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .btn-order-now:hover {
            background-color: #ffa500;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #282828;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #333333;
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .dropdown-divider {
            border-color: #333333;
        }

        /* Dark Mode for Home Page Components */
        [data-bs-theme="dark"] .hero-section {
            background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .hero-title {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .hero-subtitle {
            color: #b0b0b0;
        }

        [data-bs-theme="dark"] .btn-menu {
            border-color: #ff8c00;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .btn-menu:hover {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .btn-order {
            background-color: #ff5252;
            border-color: #ff5252;
        }

        [data-bs-theme="dark"] .btn-order:hover {
            background-color: #ff3838;
            border-color: #ff3838;
        }

        [data-bs-theme="dark"] .hero-badge {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .features-section {
            background-color: #1e1e1e;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .feature-card h3 {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .feature-card p {
            color: #b0b0b0;
        }

        [data-bs-theme="dark"] .feature-icon {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .feature-card:hover .feature-icon {
            background-color: #ff5252;
        }

        [data-bs-theme="dark"] .popular-menu-section {
            background-color: #1e1e1e;
        }

        [data-bs-theme="dark"] .section-header h2 {
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .section-header p {
            color: #b0b0b0;
        }

        [data-bs-theme="dark"] .menu-item {
            background-color: #282828;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .menu-info h3 {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .menu-badge {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .menu-badge.hot {
            background-color: #ff5252;
        }

        [data-bs-theme="dark"] .menu-badge.new {
            background-color: #4CAF50;
        }

        [data-bs-theme="dark"] .menu-price {
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .btn-add-to-cart {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .btn-add-to-cart:hover {
            background-color: #ff5252;
            color: #ffffff;
        }

        [data-bs-theme="dark"] .btn-view-all {
            border-color: #ff8c00;
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .btn-view-all:hover {
            background-color: #ff8c00;
            color: #121212;
        }

        [data-bs-theme="dark"] .testimonial-section {
            background-color: #1e1e1e;
        }

        [data-bs-theme="dark"] .testimonial-card {
            background-color: #282828;
            border-top: 3px solid #ff8c00;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .testimonial-content:before {
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .testimonial-content p {
            color: #b0b0b0;
        }

        [data-bs-theme="dark"] .testimonial-info h4 {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .promo-section {
            background: linear-gradient(135deg, #981f28 0%, #d32f2f 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .btn-app {
            background-color: #282828;
            color: #ff8c00;
        }

        [data-bs-theme="dark"] .btn-app:hover {
            background-color: #ff8c00;
            color: #121212;
        }
</style>
@endsection
