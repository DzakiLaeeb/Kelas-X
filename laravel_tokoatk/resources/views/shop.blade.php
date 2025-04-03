@extends('layouts.app')

@section('title', 'Shop')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Shop specific styles */

    /* Pagination Styles */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination li {
        margin: 0 3px;
        display: inline-flex;
    }

    .pagination li a, .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 10px;
        text-decoration: none;
        color: #3498db;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination li.active span {
        background-color: #3498db;
        color: white;
        border-color: #3498db;
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.2);
    }

    .pagination li a:hover {
        background-color: #f8f9fa;
        border-color: #3498db;
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.1);
    }

    .pagination li.disabled span {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        opacity: 0.6;
    }

    /* Fix for pagination on mobile */
    @media (max-width: 576px) {
        .pagination li a, .pagination li span {
            min-width: 35px;
            height: 35px;
            font-size: 13px;
        }

        .pagination li:not(.active):not(:first-child):not(:last-child):not(.disabled) {
            display: none;
        }
    }

    /* Shop specific variables */
    :root {
        /* Blue and White Theme */
        --accent-color: #2196f3; /* Another blue shade */
        --danger-color: #e74c3c;
        --hover-bg: #e3f2fd;
    }

    /* Dark Theme */
    [data-theme="dark"] {
        --bg-color: #0a1929; /* Dark blue background */
        --accent-color: #29b6f6;
        --danger-color: #e74c3c;
        --hover-bg: #1a3a5f;
        }

    /* End of shop specific styles */
</style>
@endpush

@section('content')

<!-- Main content -->

        /* Enhanced Header */
        header {
            background-color: var(--header-bg);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }

        header.shrink {
            padding: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header.shrink .logo {
            font-size: 1.4rem;
        }

        header.shrink .nav-menu {
            font-size: 0.95rem;
        }

        header.shrink .auth-nav .btn {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }

        header.shrink .user-greeting {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            letter-spacing: -0.5px;
        }

        .logo:hover {
            color: var(--primary-hover);
            transform: scale(1.05);
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
            justify-content: center;
        }

        .nav-menu a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover {
            color: var(--primary-color);
            background-color: var(--hover-bg);
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .nav-menu a i {
            margin-right: 0.5rem;
        }

        .auth-nav {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .auth-nav a {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-link {
            position: relative;
            color: var(--text-color);
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .cart-link:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .cart-count {
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            margin-left: 1px;
            position: relative;
            top: -15px;
        }

        /* User Menu */
        .user-greeting {
            font-weight: 600;
            color: var(--text-color);
            background-color: var(--secondary-color);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-greeting:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .dropdown-item {
            color: var(--text-color);
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
        }

        .dropdown-divider {
            border-top: 1px solid var(--border-color);
            margin: 0.5rem 0;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-danger:hover {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color) !important;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            border: 2px solid var(--primary-color);
            border-radius: 30px;
            cursor: pointer;
            background-color: transparent;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-icon {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background-color: var(--hover-bg);
            transform: translateY(-2px);
        }

        /* Shop Container */
        .shop-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .shop-title {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--text-color);
            position: relative;
            font-weight: 700;
        }

        .shop-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--primary-color);
            margin: 1rem auto;
            border-radius: 2px;
        }

        /* Product Cards */
        .card {
            background-color: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .card-img-top {
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-title {
            color: var(--text-color);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .card-text strong {
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .card-footer {
            background-color: transparent;
            padding: 1rem;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Main Content */
        main.container {
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        main h2 {
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        main h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        /* Footer */
        footer {
            background-color: var(--footer-bg);
            color: #ecf0f1;
            padding: 4rem 0 2rem;
            margin-top: auto;
        }

        footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        footer h5:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary-color);
        }

        footer a {
            color: #bbdefb;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: #fff;
            text-decoration: none;
        }

        /* Search Container */
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            flex: 1;
            margin: 0;
            padding: 1.2rem 1.5rem;
            border: 2px solid #3498db;
            border-radius: 30px 0 0 30px;
            outline: none;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            min-width: 0; /* Allow input to shrink */
        }

        .search-button {
            padding: 1.2rem 2rem;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .search-button:hover {
            background: linear-gradient(45deg, #2980b9, #2573a7);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
        }

        /* Dark Mode Compatibility */
        [data-theme="dark"] .search-container {
            background: rgba(44, 62, 80, 0.95);
        }

        [data-theme="dark"] .search-input {
            color: #ecf0f1;
            border-color: #34495e;
            background: transparent;
        }

        [data-theme="dark"] .search-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .nav-menu {
                gap: 1.5rem;
            }

            .shop-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-wrap: wrap;
            }

            .nav-menu {
                order: 3;
                width: 100%;
                justify-content: space-between;
                margin-top: 1rem;
                gap: 0.5rem;
            }

            .shop-title {
                font-size: 1.75rem;
            }

            .search-container {
                flex-direction: column;
                gap: 1rem;
            }

            .search-input {
                width: 100%;
                border-radius: 30px;
            }

            .search-button {
                width: 100%;
                border-radius: 30px;
            }
        }

        @media (max-width: 576px) {
            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-menu a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .auth-nav {
                margin-left: 0;
            }

            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .search-input,
            .search-button {
                padding: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="nav-container">
                <a href="{{ route('home') }}" class="logo">TokoATK</a>
                <nav class="nav-menu">
                    <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                    <a href="{{ route('shop') }}"><i class="fas fa-shopping-cart"></i> Produk</a>
                    <a href="{{ route('tentangkami') }}"><i class="fas fa-info-circle"></i> Tentang Kami</a>
                    <a href="{{ route('hubungikami') }}"><i class="fas fa-envelope"></i> Hubungi Kami</a>
                </nav>

                <div class="auth-nav">
                    <a href="{{ route('cart') }}"><i class="fas fa-shopping-basket"></i>
                        @if(session()->has('cart_count') && session('cart_count') > 0)
                            <span class="cart-count">{{ session('cart_count') }}</span>
                        @endif
                    </a>
                    <button id="darkModeToggle" class="btn-icon">
                        <i class="fas fa-moon"></i>
                    </button>
                    @if(auth()->check())
                        <div class="dropdown">
                            <button class="btn dropdown-toggle user-greeting" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Halo, {{ auth()->user()->name }}!
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-shopping-bag me-2"></i>Pesanan</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn">Masuk</a>
                        <a href="{{ route('register') }}" class="btn">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="search-container">
        <form action="{{ route('search') }}" method="POST" id="searchForm">
            @csrf
            <input type="text" name="search" class="search-input" placeholder="Cari produk..." value="{{ $searchQuery ?? '' }}">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <main class="container py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Our Products</h2>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="mb-0">Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</p>
            </div>
            <div>
                <select class="form-select" id="sortProducts">
                    <option value="newest">Newest First</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="name_asc">Name: A to Z</option>
                    <option value="name_desc">Name: Z to A</option>
                </select>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            @if (count($products) > 0)
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100">
                            @php
                                $imageSrc = asset('images/placeholder.png');
                                if ($product->gambar) {
                                    if (filter_var($product->gambar, FILTER_VALIDATE_URL)) {
                                        $imageSrc = $product->gambar;
                                    } else {
                                        $imageSrc = asset('storage/' . $product->gambar);
                                    }
                                }
                            @endphp

                            <img src="{{ $imageSrc }}"
                                 class="card-img-top"
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='{{ asset('images/placeholder.png') }}'">

                            <!-- Debug info - hidden
                            Image path: {{ $product->gambar }}
                            Is URL: {{ filter_var($product->gambar, FILTER_VALIDATE_URL) ? 'Yes' : 'No' }}
                            Image src: {{ $imageSrc }}
                            -->

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">
                                    <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Stock: {{ $product->stock }}</small>
                                </p>
                            </div>

                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', ['id' => $product->id]) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    @if ($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No products available.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-4 mb-5">
            {{ $products->onEachSide(1)->links('pagination.simple') }}
        </div>

        <!-- Pagination Info -->
        <div class="text-center mb-5 text-muted">
            <small>Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() ?? 0 }} produk</small>
        </div>


    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>Your trusted source for quality stationery and office supplies.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop') }}">Shop</a></li>
                        <li><a href="{{ route('tentangkami') }}">About</a></li>
                        <li><a href="{{ route('hubungikami') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p>Email: info@atkstore.com</p>
                    <p>Phone: (123) 456-7890</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle product sorting
            $('#sortProducts').change(function() {
                const sortValue = $(this).val();
                let url = new URL(window.location.href);

                // Add or update the sort parameter
                url.searchParams.set('sort', sortValue);

                // Redirect to the new URL
                window.location.href = url.toString();
            });

            // Set the selected option based on the current URL
            const urlParams = new URLSearchParams(window.location.search);
            const sortParam = urlParams.get('sort');
            if (sortParam) {
                $('#sortProducts').val(sortParam);
            }
        });
    </script>
    <script>
        // Dark mode functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;

            // Check for saved theme preference
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme) {
                body.setAttribute('data-theme', currentTheme);
                if (currentTheme === 'dark') {
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            }

            // Toggle dark mode
            darkModeToggle.addEventListener('click', function() {
                if (body.getAttribute('data-theme') === 'dark') {
                    body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                } else {
                    body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            });
        });

        // Shrinking navbar functionality
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const logo = document.querySelector('.logo');
            const navMenu = document.querySelector('.nav-menu');

            if (window.scrollY > 50) {
                header.classList.add('shrink');
            } else {
                header.classList.remove('shrink');
            }
        });

        // Category filter
        document.querySelectorAll('.category-filter').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelector('.category-filter.active').classList.remove('active');
                button.classList.add('active');
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $('.add-to-cart-form').submit(function(e) {
            e.preventDefault();
            const form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Produk berhasil ditambahkan ke keranjang!');
                        // Update cart count if needed
                        if (response.cart_count) {
                            $('.cart-count').text(response.cart_count);
                        }
                    } else {
                        alert(response.message || 'Gagal menambahkan produk ke keranjang.');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Gagal menambahkan produk ke keranjang.');
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
</qodoArtifact>












