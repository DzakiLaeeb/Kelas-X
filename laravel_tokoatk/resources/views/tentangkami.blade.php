@extends('layouts.app')

@section('title', 'Tentang Kami')

@push('styles')
<style>
    /* Additional variables for about page */
    :root {
        --hero-text-color: white;
        --hero-overlay: rgba(0, 0, 0, 0.6);
        --cta-text-color: white;
        --cta-btn-color: var(--primary-color);
        --cta-btn-bg: white;
        --cta-btn-shadow: rgba(0, 0, 0, 0.2);
    }

    body[data-theme="dark"] {
        --hero-text-color: white;
        --hero-overlay: rgba(0, 0, 0, 0.7);
        --cta-text-color: white;
        --cta-btn-color: var(--primary-color);
        --cta-btn-bg: #f8f9fa;
        --cta-btn-shadow: rgba(0, 0, 0, 0.4);
    }

    .hero-section {
        background: linear-gradient(var(--hero-overlay), var(--hero-overlay)), url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1935&q=80');
        background-size: cover;
        background-position: center;
        color: var(--hero-text-color);
        padding: 100px 0;
        text-align: center;
        margin-bottom: 50px;
        transition: background 0.3s ease;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .about-section {
        padding: 50px 0;
    }

    .about-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--text-color);
        text-align: center;
    }

    .about-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-color);
        margin-bottom: 30px;
    }

    .about-image {
        border-radius: 10px;
        box-shadow: var(--shadow-md);
        width: 100%;
        height: auto;
    }

    .values-section {
        background-color: var(--bg-color);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 70px 0;
        margin: 50px 0;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .values-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 50px;
        text-align: center;
        color: var(--text-color);
    }

    .value-card {
        background-color: var(--card-bg);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-md);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .value-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .value-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--text-color);
    }

    .value-text {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-secondary);
    }

    .team-section {
        padding: 70px 0;
    }

    .team-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 50px;
        text-align: center;
        color: var(--text-color);
    }

    .team-card {
        background-color: var(--card-bg);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .team-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .team-info {
        padding: 20px;
        text-align: center;
    }

    .team-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-color);
    }

    .team-position {
        font-size: 1rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .team-bio {
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--text-secondary);
        margin-bottom: 15px;
    }

    .team-social {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .team-social a {
        color: var(--text-secondary);
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .team-social a:hover {
        color: var(--primary-color);
    }

    .cta-section {
        background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
        padding: 70px 0;
        text-align: center;
        color: var(--cta-text-color);
        border-radius: 15px;
        margin: 50px 0;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .cta-text {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto 30px;
    }

    .cta-btn {
        background-color: var(--cta-btn-bg);
        color: var(--cta-btn-color);
        padding: 15px 30px;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        display: inline-block;
    }

    .cta-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px var(--cta-btn-shadow);
        text-decoration: none;
        color: var(--cta-btn-color);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .about-title, .values-title, .team-title, .cta-title {
            font-size: 2rem;
        }

        .value-card, .team-card {
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Tentang Kami</h1>
            <p class="hero-subtitle">Kami adalah toko alat tulis terkemuka yang berkomitmen untuk menyediakan produk berkualitas tinggi dengan harga terjangkau.</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="Tentang Kami" class="about-image">
                </div>
                <div class="col-lg-6">
                    <h2 class="about-title">Cerita Kami</h2>
                    <p class="about-text">
                        Didirikan pada tahun 2010, TokoATK telah menjadi mitra tepercaya bagi siswa, profesional, dan bisnis dalam memenuhi kebutuhan alat tulis mereka. Kami memulai perjalanan kami sebagai toko kecil dengan visi besar: menyediakan alat tulis berkualitas tinggi yang menginspirasi kreativitas dan produktivitas.
                    </p>
                    <p class="about-text">
                        Selama lebih dari satu dekade, kami telah berkembang menjadi salah satu toko alat tulis terkemuka di Indonesia, dengan ribuan pelanggan setia yang mengandalkan kami untuk kebutuhan alat tulis mereka. Kami bangga dengan reputasi kami dalam menyediakan produk berkualitas, layanan pelanggan yang luar biasa, dan pengalaman belanja yang menyenangkan.
                    </p>
                    <p class="about-text">
                        Kami percaya bahwa alat tulis yang tepat dapat membuat perbedaan besar dalam cara kita bekerja, belajar, dan mengekspresikan diri. Itulah mengapa kami berkomitmen untuk menawarkan berbagai produk terbaik dari merek terkemuka di seluruh dunia.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="values-title">Nilai-Nilai Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="value-title">Kualitas</h3>
                        <p class="value-text">
                            Kami hanya menawarkan produk berkualitas tinggi yang telah kami uji dan percayai. Kami bekerja sama dengan merek terkemuka untuk memastikan bahwa pelanggan kami mendapatkan yang terbaik.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Kepuasan Pelanggan</h3>
                        <p class="value-text">
                            Kepuasan pelanggan adalah prioritas utama kami. Kami berusaha keras untuk memberikan pengalaman belanja yang menyenangkan dan layanan pelanggan yang luar biasa.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3 class="value-title">Keberlanjutan</h3>
                        <p class="value-text">
                            Kami berkomitmen untuk mengurangi dampak lingkungan kami dengan menawarkan produk ramah lingkungan dan mengadopsi praktik bisnis yang berkelanjutan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="team-title">Tim Kami</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80" alt="CEO" class="team-image">
                        <div class="team-info">
                            <h3 class="team-name">Budi Santoso</h3>
                            <p class="team-position">CEO & Founder</p>
                            <p class="team-bio">
                                Budi mendirikan TokoATK pada tahun 2010 dengan visi untuk menyediakan alat tulis berkualitas tinggi yang menginspirasi kreativitas dan produktivitas.
                            </p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1376&q=80" alt="COO" class="team-image">
                        <div class="team-info">
                            <h3 class="team-name">Siti Rahayu</h3>
                            <p class="team-position">COO</p>
                            <p class="team-bio">
                                Siti bergabung dengan TokoATK pada tahun 2012 dan telah memainkan peran penting dalam pertumbuhan dan kesuksesan perusahaan.
                            </p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80" alt="CTO" class="team-image">
                        <div class="team-info">
                            <h3 class="team-name">Andi Wijaya</h3>
                            <p class="team-position">CTO</p>
                            <p class="team-bio">
                                Andi memimpin pengembangan platform e-commerce kami, memastikan pengalaman belanja online yang lancar dan aman bagi pelanggan kami.
                            </p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Bergabunglah dengan Komunitas Kami</h2>
            <p class="cta-text">
                Dapatkan informasi tentang produk terbaru, penawaran khusus, dan tips produktivitas dengan berlangganan newsletter kami.
            </p>
            <a href="{{ route('hubungikami') }}" class="cta-btn">Hubungi Kami</a>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Dark mode is handled by app.blade.php
        // This script is only for smooth scrolling
    });
</script>
@endpush
