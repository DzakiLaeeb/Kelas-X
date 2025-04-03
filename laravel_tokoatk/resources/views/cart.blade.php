<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TokoATK</title>
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

        /* Cart Styles */
        .cart-container {
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .cart-title {
            color: var(--text-color);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .cart-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .table {
            color: var(--text-color);
        }

        .table thead th {
            border-top: none;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-color);
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
        <div class="cart-container">
            <h2 class="cart-title">Keranjang Belanja</h2>

        @if($products->isEmpty())
            <div class="alert alert-info">
                Keranjang belanja Anda kosong. <a href="{{ route('shop') }}" class="alert-link">Lanjutkan belanja</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item['product']->gambar }}"
                                             alt="{{ $item['product']->nama_produk }}"
                                             class="img-thumbnail me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                        <span>{{ $item['product']->nama_produk }}</span>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item['product']->harga, 0, ',', '.') }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp {{ number_format($item['product']->harga * $item['quantity'], 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Lanjutkan Belanja
                </a>
                <a href="{{ route('checkout') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Lanjutkan ke Checkout
                </a>
            </div>
        @endif
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

