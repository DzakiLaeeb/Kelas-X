<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TokoATK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Theme Variables */
        :root {
            /* Blue and White Theme */
            --bg-color: #f0f8ff; /* Light blue background */
            --card-bg: #ffffff;
            --text-color: #2c3e50;
            --text-secondary: #6c757d;
            --primary-color: #1e88e5; /* Vibrant blue */
            --primary-hover: #1565c0; /* Darker blue for hover */
            --secondary-color: #e3f2fd; /* Very light blue */
            --accent-color: #2196f3; /* Another blue shade */
            --danger-color: #e74c3c;
            --header-bg: #ffffff;
            --footer-bg: #1a237e; /* Dark blue footer */
            --border-color: #bbdefb; /* Light blue border */
            --shadow-sm: 0 4px 6px rgba(30, 136, 229, 0.1);
            --shadow-md: 0 6px 12px rgba(30, 136, 229, 0.15);
            --shadow-lg: 0 10px 25px rgba(30, 136, 229, 0.2);
            --hover-bg: #e3f2fd;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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

        .nav-menu a:hover {
            color: var(--primary-color);
            background-color: var(--hover-bg);
        }

        /* Footer Styles */
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

        /* Checkout Styles */
        .checkout-container {
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .checkout-title {
            color: var(--text-color);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .checkout-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .order-summary {
            background-color: var(--secondary-color);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .order-summary-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
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
                    <a href="{{ route('shop') }}"><i class="fas fa-store"></i> Belanja</a>
                    <a href="{{ route('tentangkami') }}"><i class="fas fa-info-circle"></i> Tentang Kami</a>
                    <a href="{{ route('hubungikami') }}"><i class="fas fa-envelope"></i> Hubungi Kami</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="checkout-container">
            <h2 class="checkout-title">Checkout</h2>

            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <h4>Informasi Pengiriman</h4>

                            @if(!auth()->check())
                                <div class="alert alert-warning">
                                    Anda harus <a href="{{ route('login') }}" class="alert-link">login</a> terlebih dahulu untuk melanjutkan checkout.
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="address" name="address" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-md-5">
                                    <label for="city" class="form-label">Kota</label>
                                    <input type="text" class="form-control" id="city" name="city" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-md-4">
                                    <label for="province" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" id="province" name="province" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-md-3">
                                    <label for="postal_code" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required {{ !auth()->check() ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4>Metode Pembayaran</h4>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer" checked {{ !auth()->check() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="transfer">
                                    Transfer Bank
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" {{ !auth()->check() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('cart') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                            </a>
                            <button type="submit" class="btn btn-primary" {{ !auth()->check() ? 'disabled' : '' }}>
                                <i class="fas fa-check"></i> Selesaikan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="order-summary">
                        <h4 class="order-summary-title">Ringkasan Pesanan</h4>

                        @foreach($products as $item)
                            <div class="order-item">
                                <div>
                                    <span>{{ $item['product']->nama_produk }}</span>
                                    <small class="d-block text-muted">{{ $item['quantity'] }} x Rp {{ number_format($item['product']->harga, 0, ',', '.') }}</small>
                                </div>
                                <div>
                                    Rp {{ number_format($item['product']->harga * $item['quantity'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        <div class="order-total">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
