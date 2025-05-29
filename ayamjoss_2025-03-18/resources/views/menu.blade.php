@extends('layouts.app')

@section('content')
<!-- Tambahkan meta CSRF di head -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Tambahkan elemen untuk animasi di awal content -->
<div id="flyingCart" class="flying-cart">
    <i class="fas fa-shopping-cart"></i>
</div>

<div class="menu-container">
    <!-- Add search form at the top -->
    <div class="search-container mb-4">
        <form action="{{ route('menu.search') }}" method="GET" class="search-form">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control search-input"
                       placeholder="Cari menu..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="menu-title">Menu Spesial Kami</h2>
            <p class="menu-subtitle">Nikmati kelezatan ayam goreng dengan resep rahasia turun-temurun</p>
        </div>
    </div>

    <div class="row">
        @if(isset($menus) && count($menus) > 0)
            @foreach($menus as $menu)
                <div class="col-md-4 mb-4">
                    <div class="menu-card">
                        <div class="menu-image">
                            @if($menu->gambar)
                                <img src="{{ $menu->gambar }}"
                                     alt="{{ $menu->nama }}"
                                     class="menu-image"
                                     onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                            @else
                                <img src="https://placehold.co/400x300?text=No+Image"
                                     alt="No Image Available"
                                     class="menu-image">
                            @endif
                            @if($menu->badge)
                                <div class="menu-badge {{ $menu->badge_type }}">{{ $menu->badge }}</div>
                            @endif
                        </div>
                        <div class="menu-content">
                            <h3 class="menu-item-title">{{ $menu->nama }}</h3>
                            <p class="menu-description">{{ $menu->deskripsi }}</p>
                            <div class="menu-price-action">
                                <span class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); addToCart({{ $menu->idmenu }})">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $menu->idmenu }}">
                                    <input type="hidden" name="menu" value="{{ $menu->nama }}">
                                    <input type="hidden" name="harga" value="{{ $menu->harga }}">
                                    <input type="hidden" name="gambar" value="{{ $menu->gambar }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="btn btn-add-cart" data-menu-id="{{ $menu->idmenu }}">
                                        <i class="fas fa-shopping-cart"></i> Pesan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                @if(request('search'))
                    <p class="no-results">Tidak ada menu yang cocok dengan pencarian "{{ request('search') }}"</p>
                @else
                    <p class="no-results">Tidak ada menu tersedia.</p>
                @endif
            </div>
        @endif
    </div>

    <!-- Paket Spesial -->
    <div class="row mt-5">
        <div class="col-12 text-center mb-4">
            <h2 class="menu-section-title">Paket Spesial</h2>
        </div>

        <div class="col-md-6 mb-4">
            <div class="menu-card special">
                <div class="menu-image">
                    <img src="https://th.bing.com/th/id/OIG3.8Mea1K8aqUnLZtapHuhQ?pid=ImgGn" alt="Paket Keluarga">
                    <div class="menu-badge promo">Hemat 15%</div>
                </div>
                <div class="menu-content">
                    <h3 class="menu-item-title">Paket Keluarga</h3>
                    <p class="menu-description">4 potong ayam, 2 nasi, 2 minuman, dan 2 camilan spesial.</p>
                    <div class="menu-price-action">
                        <span class="menu-price">Rp 95.000</span>
                        <form onsubmit="event.preventDefault(); addToCart('paket-keluarga')" class="d-inline">
                            <button type="submit" class="btn btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="menu-card special">
                <div class="menu-image">
                    <img src="https://images.unsplash.com/photo-1606755962773-d324e0a13086?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Paket Berdua">
                    <div class="menu-badge promo">Hemat 10%</div>
                </div>
                <div class="menu-content">
                    <h3 class="menu-item-title">Paket Berdua</h3>
                    <p class="menu-description">2 potong ayam, 2 nasi, dan 2 minuman pilihan.</p>
                    <div class="menu-price-action">
                        <span class="menu-price">Rp 55.000</span>
                        <form onsubmit="event.preventDefault(); addToCart('paket-berdua')" class="d-inline">
                            <button type="submit" class="btn btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showFlyingCartAnimation(event, form) {
    event.preventDefault();

    // Get the flying cart element
    const flyingCart = document.getElementById('flyingCart');

    // Clone the cart icon from the clicked button
    const cartButton = form.querySelector('.btn-add-cart');
    const cartIcon = cartButton.querySelector('i').cloneNode(true);

    // Create a clone of the cart icon to animate
    const animatedIcon = document.createElement('div');
    animatedIcon.classList.add('animated-cart-icon');
    animatedIcon.appendChild(cartIcon);

    // Position the animated icon at the button
    const buttonRect = cartButton.getBoundingClientRect();
    animatedIcon.style.position = 'fixed';
    animatedIcon.style.left = `${buttonRect.left}px`;
    animatedIcon.style.top = `${buttonRect.top}px`;
    animatedIcon.style.zIndex = '1000';

    // Append to body for animation
    document.body.appendChild(animatedIcon);

    // Animate the icon to the cart
    const cartRect = flyingCart.getBoundingClientRect();
    animatedIcon.animate([
        {
            transform: `translate(0, 0)`,
            opacity: 1
        },
        {
            transform: `translate(${cartRect.left - buttonRect.left}px, ${cartRect.top - buttonRect.top}px)`,
            opacity: 0.5
        }
    ], {
        duration: 800,
        easing: 'ease-in-out'
    });

    // Send AJAX request to add item to cart
    const formData = new FormData(form);

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Optional: Update cart count or show notification
        if (data.success) {
            // You can add a success notification or update cart count here
            console.log('Item added to cart:', data);
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
    })
    .finally(() => {
        // Remove animated icon after animation
        setTimeout(() => {
            document.body.removeChild(animatedIcon);
        }, 1000);
    });
}

// For special package buttons without form
function addToCart(packageType) {
    const flyingCart = document.getElementById('flyingCart');

    // Prepare data for special packages
    const packageDetails = {
        'paket-keluarga': {
            id: 'paket-keluarga',
            menu: 'Paket Keluarga',
            harga: 95000,
            gambar: 'https://th.bing.com/th/id/OIG3.8Mea1K8aqUnLZtapHuhQ?pid=ImgGn',
            jumlah: 1
        },
        'paket-berdua': {
            id: 'paket-berdua',
            menu: 'Paket Berdua',
            harga: 55000,
            gambar: 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYglfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80',
            jumlah: 1
        }
    };

    const packageData = packageDetails[packageType];

    // Create a form-like data object
    const formData = new FormData();
    formData.append('id', packageData.id);
    formData.append('menu', packageData.menu);
    formData.append('harga', packageData.harga);
    formData.append('gambar', packageData.gambar);
    formData.append('jumlah', packageData.jumlah);

    // Animate the flying cart
    const animatedIcon = document.createElement('div');
    animatedIcon.classList.add('animated-cart-icon');
    animatedIcon.innerHTML = '<i class="fas fa-shopping-cart"></i>';

    const cartButton = event.target;
    const buttonRect = cartButton.getBoundingClientRect();
    animatedIcon.style.position = 'fixed';
    animatedIcon.style.left = `${buttonRect.left}px`;
    animatedIcon.style.top = `${buttonRect.top}px`;
    animatedIcon.style.zIndex = '1000';

    document.body.appendChild(animatedIcon);

    const cartRect = flyingCart.getBoundingClientRect();
    animatedIcon.animate([
        {
            transform: `translate(0, 0)`,
            opacity: 1
        },
        {
            transform: `translate(${cartRect.left - buttonRect.left}px, ${cartRect.top - buttonRect.top}px)`,
            opacity: 0.5
        }
    ], {
        duration: 800,
        easing: 'ease-in-out'
    });

    // Send AJAX request to add item to cart
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Optional: Update cart count or show notification
        if (data.success) {
            console.log('Item added to cart:', data);
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
    })
    .finally(() => {
        // Remove animated icon after animation
        setTimeout(() => {
            document.body.removeChild(animatedIcon);
        }, 1000);
    });
}

function addToCartAndRedirect(menuId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Buat FormData untuk mengirim data
    const formData = new FormData();
    formData.append('id', menuId);
    formData.append('_token', token);

    console.log('Redirecting to cart with menuId:', menuId);

    fetch('/cart/add', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if(data.success) {
            window.location.href = '/cart';
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menambahkan ke keranjang: ' + error.message);
    });
}
    </script>
</div>

<style>
    .menu-container {
        padding: 20px 0;
        background-color: var(--bg-color);
        transition: background-color 0.3s ease;
    }

    /* Light mode styles */
    :root {
        --bg-color: #f9f9f9;
        --card-bg: white;
        --text-color: #333;
        --text-secondary: #666;
    }

    /* Dark mode styles */
    [data-bs-theme="dark"] {
        --bg-color: #1e1e1e;
        --card-bg: #282828;
        --text-color: #e0e0e0;
        --text-secondary: #b0b0b0;
    }

    .menu-card {
        background-color: var(--card-bg);
        color: var(--text-color);
    }

    .menu-description {
        color: var(--text-secondary);
    }

    .menu-title {
        color: var(--primary-red);
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
    }

    .menu-title:after {
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

    .menu-subtitle {
        color: var(--dark-brown);
        font-size: 1.1rem;
        margin-top: 20px;
    }

    .menu-section-title {
        color: var(--primary-red);
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
    }

    .menu-section-title:after {
        content: "";
        position: absolute;
        width: 40%;
        height: 3px;
        background-color: var(--primary-yellow);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .menu-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .menu-card.special {
        border: 2px solid var(--primary-yellow);
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

    .menu-card:hover .menu-image img {
        transform: scale(1.1);
    }

    .menu-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: #ff8c00; /* Changed to orange for normal badge */
        color: white; /* Changed text color to white for better contrast */
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: var(--badge-shadow);
    }

    .menu-badge.hot {
        background-color: var(--primary-red);
        color: white;
    }

    .menu-badge.new {
        background-color: #4CAF50;
        color: white;
    }

    .menu-badge.promo {
        background-color: #9C27B0;
        color: white;
    }

    /* Dark mode adjustments if needed */
    [data-bs-theme="dark"] .menu-badge {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .menu-content {
        padding: 20px;
    }

    .menu-item-title {
        color: var(--dark-brown);
        font-weight: 600;
        font-size: 1.3rem;
        margin-bottom: 10px;
    }

    .menu-description {
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin-bottom: 15px;
        min-height: 60px;
    }

    .menu-price-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-price {
        font-weight: 700;
        color: var(--primary-red);
        font-size: 1.2rem;
    }

    .btn-add-cart {
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        border: none;
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        background-color: var(--primary-red);
        color: white;
    }

    /* Add dark mode specific styles */
    [data-bs-theme="dark"] .btn-add-cart {
        background-color: var(--primary-yellow);
        color: #121212;  /* Dark text color for better contrast on yellow background */
    }

    [data-bs-theme="dark"] .btn-add-cart:hover {
        background-color: var(--primary-red);
        color: white;
    }

    @media (max-width: 768px) {
        .menu-description {
            min-height: auto;
        }

        .theme-toggle-container {
            position: relative;
            top: 0;
            right: 0;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
    }

    /* Add these new styles */
    .search-container {
        max-width: 600px;
        margin: 0 auto 2rem;
        padding: 0 15px;
    }

    .search-form {
        width: 100%;
    }

    .search-input {
        border-radius: 30px 0 0 30px;
        border: 2px solid var(--primary-yellow);
        padding: 12px 20px;
        font-size: 1rem;
        background-color: var(--card-bg);
        color: var(--text-color);
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px var(--primary-yellow);
        border-color: var(--primary-yellow);
    }

    .btn-search {
        border-radius: 0 30px 30px 0;
        background-color: var(--primary-yellow);
        color: var(--dark-brown);
        border: 2px solid var(--primary-yellow);
        padding: 0 25px;
        transition: all 0.3s ease;
    }

    .btn-search:hover {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
        color: white;
    }

    /* Dark mode styles */
    [data-bs-theme="dark"] .search-input {
        background-color: #282828;
        color: #e0e0e0;
        border-color: var(--primary-yellow);
    }

    [data-bs-theme="dark"] .search-input::placeholder {
        color: #888;
    }

    [data-bs-theme="dark"] .btn-search {
        background-color: var(--primary-yellow);
        color: #121212;
    }

    [data-bs-theme="dark"] .btn-search:hover {
        background-color: var(--primary-red);
        color: white;
    }

    .no-results {
        color: var(--text-color);
        font-size: 1.1rem;
        padding: 20px;
        background-color: var(--card-bg);
        border-radius: 10px;
        box-shadow: var(--card-shadow);
    }

    [data-bs-theme="dark"] .no-results {
        background-color: #282828;
        color: #e0e0e0;
    }
</style>
@endsection

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-add-cart');
    const navCart = document.querySelector('.nav-link[href*="cart"]');
    const flyingCart = document.getElementById('flyingCart');

    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Tambahkan class clicked untuk efek shake
            this.classList.add('clicked');

            const buttonRect = this.getBoundingClientRect();
            const navCartRect = navCart.getBoundingClientRect();

            // Set posisi awal dan ukuran yang lebih besar
            flyingCart.style.top = `${buttonRect.top}px`;
            flyingCart.style.left = `${buttonRect.left}px`;
            flyingCart.style.width = '60px';
            flyingCart.style.height = '60px';

            // Aktifkan animasi dengan ukuran awal besar
            flyingCart.classList.add('active');

            // Animasi ke navbar cart
            setTimeout(() => {
                flyingCart.style.top = `${navCartRect.top}px`;
                flyingCart.style.left = `${navCartRect.left}px`;
                flyingCart.style.width = '30px';
                flyingCart.style.height = '30px';

                navCart.classList.add('bounce');
            }, 100);

            // Reset animasi setelah animasi selesai
            setTimeout(() => {
                flyingCart.classList.remove('active');
                navCart.classList.remove('bounce');
                this.classList.remove('clicked');
            }, 1000);
        });
    });
});
</script>

<style>
.flying-cart {
    position: fixed;
    width: 60px;
    height: 60px;
    background-color: #ffc107;
    color: #000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    opacity: 0;
    z-index: 9999;
    transition: all 1.5s cubic-bezier(0.25, 0.1, 0.25, 1.4); /* Transisi lebih lambat dan bouncy */
    box-shadow: 0 0 15px rgba(255, 193, 7, 0.5); /* Tambah glow effect */
}

.flying-cart.active {
    opacity: 1;
    transform-origin: center;
    animation: spin 1.5s ease-in-out; /* Tambah animasi spin */
}

.flying-cart i {
    font-size: 2rem; /* Icon lebih besar */
    color: #000;
}

/* Animasi spin untuk flying cart */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Efek bounce pada cart navbar yang lebih kentara */
@keyframes cartBounce {
    0% { transform: scale(1); }
    25% { transform: scale(1.4); }
    50% { transform: scale(0.8); }
    75% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.nav-link[href*="cart"].bounce i {
    animation: cartBounce 0.8s ease-in-out;
    color: #ffc107; /* Warna berubah saat bounce */
}

/* Efek shake pada button yang lebih kentara */
@keyframes buttonShake {
    0% { transform: translateX(0) scale(1); }
    25% { transform: translateX(-8px) scale(1.1); }
    50% { transform: translateX(8px) scale(1.1); }
    75% { transform: translateX(-8px) scale(1.1); }
    100% { transform: translateX(0) scale(1); }
}

.btn-add-cart.clicked {
    animation: buttonShake 0.5s ease-in-out;
    pointer-events: none; /* Prevent multiple clicks during animation */
}

/* Style untuk button yang lebih menarik */
.btn-add-cart {
    display: inline-block;
    padding: 10px 20px;
    background-color: #ffc107;
    color: #000;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-add-cart:hover {
    background-color: #ffb300;
    transform: translateY(-3px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

.btn-add-cart i {
    margin-right: 8px;
}
</style>
@endpush
