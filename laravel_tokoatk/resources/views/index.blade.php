
@extends('layouts.app')

@section('title', 'Toko Online')

@php
    use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    :root {
        /* Light mode variables */
        --primary-color: #3498db;
        --secondary-color: #2ecc71;
        --accent-color: #f39c12;
        --text-color: #2c3e50;
        --light-text: #7f8c8d;
        --card-bg: #ffffff;
        --bg-light: #f8f9fa;
        --bg-main: #ffffff;
        --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 10px 15px rgba(0, 0, 0, 0.1);
        --border-radius-sm: 8px;
        --border-radius-md: 16px;
        --border-radius-lg: 24px;
        --border-color: rgba(0,0,0,0.05);
    }

    /* Dark mode variables */
    [data-theme="dark"] {
        --primary-color: #4fa3e0;
        --secondary-color: #3dd685;
        --accent-color: #f7b541;
        --text-color: #ecf0f1;
        --light-text: #bdc3c7;
        --card-bg: #2c3e50;
        --bg-light: #1a2530;
        --bg-main: #121a22;
        --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.2);
        --shadow-md: 0 10px 15px rgba(0, 0, 0, 0.3);
        --border-color: rgba(255,255,255,0.1);
    }

    body {
        background-color: var(--bg-main);
        color: var(--text-color);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Theme Toggle Button */
    .theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background-color: var(--card-bg);
        color: var(--text-color);
        border: 1px solid var(--border-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    .theme-toggle:hover {
        transform: scale(1.1);
    }

    /* Main Container Styles */
    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0;
        overflow: hidden;
    }

    /* Search Container Redesign */
    .search-container {
        background-color: var(--bg-light);
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .search-inner {
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        background: var(--card-bg);
        border-radius: 50px;
        padding: 0.5rem 1rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .search-input {
        flex: 1;
        border: none;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        outline: none;
        background: transparent;
        color: var(--text-color);
    }

    .search-button {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-button:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Banner Section Redesign */
    .banner-section {
        padding: 0;
        margin: 0 auto 3rem;
        max-width: 1200px; /* Reduced max width */
        position: relative;
    }

    #bannerCarousel {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin: 0 auto;
        width: 100%;
        max-width: 100%;
        position: relative;
        transition: all 0.3s ease;
    }

    .carousel-inner {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        width: 100%;
        aspect-ratio: 16 / 7; /* Slightly taller aspect ratio for better proportions */
        max-height: 450px; /* Reduced maximum height */
    }

    .carousel-item {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .carousel-caption {
        background: rgba(0,0,0,0.4);
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1.25rem;
        max-width: 70%;
        margin: 0 auto;
        bottom: 1.5rem;
    }

    .carousel-caption h5 {
        margin: 0;
        font-size: 1.25rem;
    }

    .carousel-indicators {
        margin-bottom: 0.75rem;
    }

    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.5);
        border: none;
        margin: 0 4px;
    }

    .carousel-indicators .active {
        background-color: white;
    }

    /* Carousel controls styling */
    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
        opacity: 0.7;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 1.5rem;
        height: 1.5rem;
    }

    /* Hide carousel controls when only one banner */
    .single-banner .carousel-indicators,
    .single-banner .carousel-control-prev,
    .single-banner .carousel-control-next {
        display: none;
    }

    /* 3D Model Container Redesign */
    .model-section {
        padding: 1rem 0 3rem;
        background: linear-gradient(180deg, var(--bg-light) 0%, var(--bg-main) 100%);
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .containerbanner {
        margin: 0 auto;
        padding: 0;
        width: 100%;
        max-width: 900px; /* Reduced max width */
        aspect-ratio: 16 / 7; /* Wider aspect ratio for smaller height */
        max-height: 350px; /* Maximum height */
        background: var(--card-bg);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        position: relative;
    }

    .containerbanner spline-viewer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: scale(0.9); /* Slightly scale down the 3D model */
    }

    /* Products Section Redesign */
    .products-section {
        padding: 4rem 0;
        background-color: var(--bg-main);
        width: 100%;
    }

    .products-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 0;
        width: 100%;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .section-title:after {
        content: '';
        position: absolute;
        width: 80px;
        height: 4px;
        background: var(--primary-color);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .section-subtitle {
        color: var(--light-text);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 1rem auto 0;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 250px);
        gap: 1.5rem;
        width: fit-content;
        justify-content: center;
        margin: 0 auto;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
    }

    @media (max-width: 1200px) {
        .featured-grid {
            grid-template-columns: repeat(2, 1fr);
            max-width: 800px;
        }
    }

    @media (max-width: 768px) {
        .featured-grid {
            grid-template-columns: 1fr;
            max-width: 400px;
        }
    }

    .view-all-btn {
        padding: 12px 24px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .view-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .product-card {
        background: var(--card-bg);
        border-radius: var(--border-radius-md);
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border-color);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .product-card a {
        text-decoration: none;
        color: var(--text-color);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        aspect-ratio: 1 / 1; /* Square aspect ratio */
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-info {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        background: var(--card-bg);
        text-align: center;
    }

    .product-category {
        font-size: 0.75rem;
        color: var(--light-text);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.8em; /* Fixed height for title (2 lines) */
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: auto;
        padding-top: 0.75rem;
    }

    /* Add to cart button */
    .add-to-cart {
        margin: 0.75rem auto 0;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90%;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .add-to-cart:hover {
        background-color: #2980b9;
    }

    .add-to-cart i {
        margin-right: 0.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(3, 250px);
            gap: 1.25rem;
        }
    }

    @media (max-width: 992px) {
        .carousel-inner {
            aspect-ratio: 16 / 8;
            max-height: 350px;
        }

        .containerbanner {
            max-width: 700px;
            aspect-ratio: 16 / 8;
            max-height: 300px;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .products-grid {
            grid-template-columns: repeat(3, 220px);
            gap: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .banner-section {
            margin-bottom: 2rem;
            max-width: 95%;
        }

        .carousel-inner {
            aspect-ratio: 16 / 9;
            max-height: 300px;
        }

        .model-section {
            padding: 1rem 0 2.5rem;
        }

        .containerbanner {
            max-width: 600px;
            aspect-ratio: 16 / 9;
            max-height: 250px;
        }

        .products-section {
            padding: 3rem 0;
        }

        .section-title {
            font-size: 2rem;
        }

        .products-grid {
            grid-template-columns: repeat(2, 200px);
            gap: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .search-inner {
            flex-direction: column;
            padding: 0.75rem;
        }

        .search-input {
            width: 100%;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
        }

        .search-button {
            width: 100%;
            padding: 0.5rem;
        }

        .banner-section {
            margin-bottom: 1.5rem;
        }

        .carousel-inner {
            aspect-ratio: 16 / 10;
            max-height: 200px;
        }

        .model-section {
            padding: 0.5rem 0 2rem;
        }

        .containerbanner {
            max-width: 95%;
            aspect-ratio: 16 / 10;
            max-height: 180px;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .section-subtitle {
            font-size: 1rem;
        }

        .products-grid {
            grid-template-columns: repeat(2, 150px);
            gap: 1rem;
        }

        .product-info {
            padding: 0.75rem;
        }

        .product-title {
            font-size: 0.9rem;
            height: 2.5em;
        }

        .product-price {
            font-size: 1.1rem;
            padding-top: 0.5rem;
        }

        .add-to-cart {
            font-size: 0.8rem;
            padding: 0.4rem;
        }
    }

    @media (max-width: 400px) {
        .containerbanner {
            max-height: 150px;
        }

        .products-grid {
            grid-template-columns: repeat(1, 250px);
            gap: 1.5rem;
        }

        .product-image-container {
            aspect-ratio: 1 / 1;
        }

        .product-card {
            width: 100%;
            margin: 0 auto;
        }
    }
</style>
@endpush

@section('content')
    <!-- Dark mode functionality is now handled by the app layout -->

    <div class="main-container">
        <!-- Banner Section -->
        <section class="banner-section">
            @php
                $bannerCount = count($banners);
                $carouselClass = $bannerCount <= 1 ? 'single-banner' : '';
            @endphp

            <div id="bannerCarousel" class="carousel slide {{ $carouselClass }}" data-bs-ride="{{ $bannerCount > 1 ? 'carousel' : 'false' }}">
                @if($bannerCount > 1)
                <div class="carousel-indicators">
                    @foreach($banners as $index => $banner)
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                @endif

                <div class="carousel-inner">
                    @forelse($banners as $index => $banner)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            @if($banner->link)
                                <a href="{{ $banner->link }}">
                            @endif

                            @if(Str::startsWith($banner->image_url, ['http://', 'https://']))
                                <img src="{{ $banner->image_url }}" class="d-block w-100" alt="{{ $banner->title }}">
                            @else
                                <img src="{{ asset('storage/' . $banner->image_url) }}" class="d-block w-100" alt="{{ $banner->title }}">
                            @endif

                            @if($banner->title)
                            <div class="carousel-caption d-none d-md-block">
                                <h5>{{ $banner->title }}</h5>
                            </div>
                            @endif

                            @if($banner->link)
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/1200x500?text=Welcome+to+TokoATK" class="d-block w-100" alt="Default Banner">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Welcome to TokoATK</h5>
                                <p>Your one-stop shop for all stationery needs</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($bannerCount > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
        </section>

        <!-- 3D Model Section -->
        <section class="model-section">
            <div class="section-header">
                <h2 class="section-title">Interactive 3D View</h2>
                <p class="section-subtitle">Explore our products in 3D</p>
            </div>
            <div class="containerbanner">
                <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.68/build/spline-viewer.js"></script>
                <spline-viewer url="https://prod.spline.design/s-0XnhNcOL15Unah/scene.splinecode"></spline-viewer>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="products-section">
            <div class="products-container">
                <div class="section-header">
                    <h2 class="section-title">Featured Products</h2>
                    <p class="section-subtitle">Discover our selection of high-quality stationery products</p>
                </div>

                <div class="products-grid featured-grid">
                    @forelse($featuredProducts as $product)
                        <div class="product-card">
                            <a href="{{ route('products.show', $product->id) }}">
                                <div class="product-image-container">
                                    @if($product->image_url)
                                        @if(Str::startsWith($product->image_url, ['http://', 'https://']))
                                            <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('storage/' . $product->image_url) }}" class="product-image" alt="{{ $product->name }}">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/300x300?text=No+Image" class="product-image" alt="{{ $product->name }}">
                                    @endif
                                </div>
                                <div class="product-info">
                                    @if(isset($product->category_name))
                                        <div class="product-category">{{ $product->category_name }}</div>
                                    @endif
                                    <h3 class="product-title">{{ $product->name }}</h3>
                                    <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <button class="add-to-cart" type="button">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No products found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('shop') }}" class="btn btn-primary view-all-btn">
                        <i class="fas fa-shopping-basket"></i> View All Products
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousel with autoplay
        const myCarousel = new bootstrap.Carousel(document.getElementById('bannerCarousel'), {
            interval: 3000,  // Time in milliseconds between slides
            ride: 'carousel',
            pause: 'hover',  // Pause on mouse hover
            wrap: true,      // Continuous loop
            touch: true      // Enable touch swiping on mobile
        });

        // Dark mode functionality is now handled by the app layout
        // Check for saved theme preference or respect OS preference
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }

        // Listen for OS theme changes
        prefersDarkScheme.addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            }
        });
    });
</script>
@endpush
