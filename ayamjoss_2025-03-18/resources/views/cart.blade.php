@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 cart-title">Keranjang Belanja</h2>

    @php
        $cart = session()->get('cart', []);
        \Log::info('Cart in view:', ['cart' => $cart]);
    @endphp
    @if(!empty($cart))
        <div class="card shadow-sm cart-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Gambar</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach($cart as $id => $details)
                                @php $total += $details['harga'] * $details['jumlah'] @endphp
                                <tr data-id="{{ $id }}" class="cart-item">
                                    <td>{{ $details['nama'] }}</td>
                                    <td>
                                        @if(isset($details['gambar']))
                                            <img src="{{ asset($details['gambar']) }}"
                                                 alt="{{ $details['nama'] }}"
                                                 class="cart-item-image"
                                                 onerror="this.src='https://placehold.co/400x300?text=No+Image'"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="https://placehold.co/400x300?text=No+Image"
                                                 alt="No Image Available"
                                                 class="cart-item-image"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($details['harga'], 0, ',', '.') }}</td>
                                    <td>
                                        <div class="quantity-controls">
                                            <button class="btn btn-sm btn-quantity decrease"
                                                    onclick="updateCart('{{ $id }}', 'decrease')">
                                                -
                                            </button>
                                            <span class="mx-2 quantity-value">{{ $details['jumlah'] }}</span>
                                            <button class="btn btn-sm btn-quantity increase"
                                                    onclick="updateCart('{{ $id }}', 'increase')">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="item-total">Rp {{ number_format($details['harga'] * $details['jumlah'], 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-remove" onclick="removeItem('{{ $id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="cart-total-row">
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold cart-grand-total">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 cart-actions">
                    <button class="btn btn-danger clear-cart-btn" onclick="clearCart()">
                        Kosongkan Keranjang
                    </button>
                    <div class="action-buttons">
                        <a href="{{ url('/menu') }}" class="btn btn-secondary continue-shopping-btn me-2">
                            Lanjut Belanja
                        </a>
                        <button type="button" class="btn btn-primary checkout-btn">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm empty-cart-card">
            <div class="card-body text-center py-5">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="mt-4">Keranjang Belanja Kosong</h3>
                <p class="text-muted">Anda belum menambahkan menu apapun ke keranjang.</p>
                <a href="{{ url('/menu') }}" class="btn btn-primary view-menu-btn">
                    Lihat Menu
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Modal Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Informasi Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkoutForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="telp" name="telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="catatan" name="catatan"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="processCheckout()">Konfirmasi Pesanan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateCart(id, action) {
    console.log('Updating cart:', id, action);
    // Add animation class before making the request
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        row.classList.add('updating');
    }

    fetch(`/cart/update/${id}/${action}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Update response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);
        if(data.success) {
            window.location.reload();
        } else {
            throw new Error('Failed to update cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengupdate keranjang');
        // Remove animation class if there's an error
        if (row) {
            row.classList.remove('updating');
        }
    });
}

function removeItem(id) {
    console.log('Removing item:', id);
    if(confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            row.classList.add('removing');
        }

        setTimeout(() => {
            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Remove response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Remove response data:', data);
                if(data.success) {
                    window.location.reload();
                } else {
                    throw new Error('Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus item: ' + error.message);
                if (row) {
                    row.classList.remove('removing');
                }
            });
        }, 300); // Wait for animation to complete
    }
}

function clearCart() {
    console.log('Clearing cart');
    if(confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        const cartCard = document.querySelector('.cart-card');
        if (cartCard) {
            cartCard.classList.add('clearing');
        }

        setTimeout(() => {
            fetch('/cart/clear', {
                method: 'POST', // Ubah dari DELETE ke POST sesuai dengan route
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Clear cart response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Clear cart response data:', data);
                if(data.success) {
                    window.location.reload();
                } else {
                    throw new Error('Failed to clear cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengosongkan keranjang: ' + error.message);
                if (cartCard) {
                    cartCard.classList.remove('clearing');
                }
            });
        }, 400); // Wait for animation to complete
    }
}

function processCheckout() {
    const form = document.getElementById('checkoutForm');
    const formData = {
        nama: document.getElementById('nama').value,
        email: document.getElementById('email').value,
        telp: document.getElementById('telp').value,
        alamat: document.getElementById('alamat').value,
        catatan: document.getElementById('catatan').value,
        cart: @json(session('cart')), // Mengambil data cart dari session
        _token: '{{ csrf_token() }}'
    };

    // Tampilkan loading spinner
    Swal.fire({
        title: 'Memproses Pesanan',
        html: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Kirim ke endpoint store order
    fetch('{{ route("order.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pesanan Berhasil!',
                text: 'Pesanan Anda telah berhasil dibuat.',
                showConfirmButton: true,
                confirmButtonText: 'Lihat Detail Pesanan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/order/success/${data.idorder}`;
                }
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat memproses pesanan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message || 'Terjadi kesalahan saat memproses pesanan'
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Checkout button click handler
    document.querySelector('.checkout-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        modal.show();
    });

    // Confirm checkout button click handler
    document.getElementById('confirmCheckout')?.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.getElementById('checkoutForm');
        if (form.checkValidity()) {
            processCheckout();
        } else {
            form.reportValidity();
        }
    });

    // Add hover effects for buttons
    const buttons = document.querySelectorAll('.btn-quantity, .btn-remove');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('btn-hover');
        });
        button.addEventListener('mouseleave', function() {
            this.classList.remove('btn-hover');
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Color Variables */
:root {
    --primary-red: #e63946;
    --secondary-red: #85d628;
    --primary-yellow: #fcbf49;
    --secondary-yellow: #f77f00;
    --light-yellow: #ffdd99;
    --dark-red: #9d0208;
    --light-bg: #fff8e6;
    --table-hover: #fff3d6;
    --gradient-primary: linear-gradient(135deg, var(--primary-red), var(--secondary-yellow));
    --gradient-hover: linear-gradient(135deg, var(--dark-red), var(--secondary-red));
}

/* Enhanced Card Styles */
.cart-card {
    backdrop-filter: blur(10px);
    background: rgba(255, 248, 230, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transform-style: preserve-3d;
    perspective: 1000px;
}

.cart-card:hover {
    transform: translateY(-5px) rotateX(2deg);
}

/* General Styles */
body {
    background-color: #fefefe;
}

.cart-title {
    font-weight: 700;
    color: var(--dark-red);
    position: relative;
    padding-bottom: 10px;
    margin-bottom: 25px;
}

.cart-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-red), var(--primary-yellow));
    border-radius: 3px;
}

.cart-card, .empty-cart-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(230, 57, 70, 0.1) !important;
    transition: all 0.3s ease;
    background-color: var(--light-bg);
}

.cart-card:hover, .empty-cart-card:hover {
    box-shadow: 0 8px 25px rgba(230, 57, 70, 0.15) !important;
    transform: translateY(-2px);
}

/* Enhanced Table Styles */
.table {
    margin-bottom: 0;
    background-color: transparent;
}

.table thead th {
    background: var(--gradient-primary);
    color: white;
    padding: 20px 15px;
    position: relative;
    overflow: hidden;
}

.table thead th::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: shimmer 2s infinite;
}

/* Enhanced Item Image */
.cart-item-image {
    border-radius: 8px !important;
    box-shadow: 0 2px 5px rgba(230, 57, 70, 0.2);
    transition: all 0.3s ease;
    width: 60px !important;
    height: 60px !important;
    border: 2px solid var(--primary-yellow);
}

.cart-item-image:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(230, 57, 70, 0.3);
    border-color: var(--secondary-red);
}

/* Enhanced Quantity Controls */
.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border-radius: 30px;
    padding: 2px;
    width: fit-content;
    margin: 0 auto;
    box-shadow: 0 2px 5px rgba(230, 57, 70, 0.1);
    border: 1px solid var(--light-yellow);
}

.btn-quantity {
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important;
    font-weight: bold;
    font-size: 16px;
    transition: all 0.2s ease;
    border: none !important;
}

.btn-quantity.decrease {
    background-color: #fff;
    color: var(--primary-red);
}

.btn-quantity.increase {
    background-color: #fff;
    color: var(--secondary-yellow);
}

.btn-quantity:hover {
    transform: scale(1.1);
}

.btn-quantity.decrease:hover {
    background-color: var(--primary-red);
    color: white;
}

.btn-quantity.increase:hover {
    background-color: var(--secondary-yellow);
    color: white;
}

.quantity-value {
    font-weight: 600;
    min-width: 30px;
    text-align: center;
    color: var(--dark-red);
}

/* Enhanced Remove Button */
.btn-remove {
    background: var(--gradient-primary);
    color: white;
    border: none;
    width: 44px;
    height: 44px;
    position: relative;
    overflow: hidden;
}

.btn-remove:hover {
    animation: shake 0.5s ease-in-out;
}

.btn-remove i {
    transition: all 0.3s ease;
}

.btn-remove:hover i {
    transform: scale(1.2) rotate(180deg);
}

/* Total Row */
.cart-total-row {
    background-color: var(--light-yellow);
}

.cart-total-row td {
    padding: 15px 10px;
}

.cart-grand-total {
    color: var(--dark-red);
    font-size: 1.1rem;
}

/* Enhanced Action Buttons */
.cart-actions {
    background: rgba(255, 248, 230, 0.5);
    padding: 20px;
    border-radius: 15px;
    backdrop-filter: blur(5px);
}

.clear-cart-btn {
    transition: all 0.3s ease;
    border-radius: 30px;
    padding: 8px 20px;
    background-color: var(--primary-red);
    border-color: var(--primary-red);
}

.clear-cart-btn:hover {
    background-color: var(--dark-red);
    border-color: var(--dark-red);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(230, 57, 70, 0.3);
}

.action-buttons .btn {
    border-radius: 30px;
    padding: 8px 20px;
    transition: all 0.3s ease;
}

.continue-shopping-btn {
    background-color: #6c757d;
    border-color: #6c757d;
}

.continue-shopping-btn:hover {
    background-color: #5a6268;
    border-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

.checkout-btn {
    background: var(--gradient-primary);
    position: relative;
    overflow: hidden;
    transition: all 0.5s ease;
}

.checkout-btn::before {
    content: '';
    position: absolute;
    top: -100%;
    left: -100%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    transition: all 0.5s ease;
}

.checkout-btn:hover::before {
    top: -50%;
    left: -50%;
    transform: scale(1.2);
}

/* Empty Cart Enhancement */
.empty-cart-card {
    background: rgba(255, 248, 230, 0.9);
    backdrop-filter: blur(10px);
}

.empty-cart-icon {
    font-size: 4rem;
    color: var(--primary-yellow);
    margin-bottom: 20px;
    animation: float 3s ease-in-out infinite;
}

.empty-cart-icon::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 10px;
    background: rgba(0,0,0,0.1);
    border-radius: 50%;
    animation: shadow 3s ease-in-out infinite;
}

.view-menu-btn {
    border-radius: 30px;
    padding: 10px 25px;
    background: linear-gradient(90deg, var(--primary-red), var(--secondary-yellow));
    border: none;
    transition: all 0.3s ease;
    margin-top: 15px;
}

.view-menu-btn:hover {
    background: linear-gradient(90deg, var(--dark-red), var(--secondary-red));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(230, 57, 70, 0.4);
}

/* New Animations */
@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(15deg); }
    75% { transform: rotate(-15deg); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@keyframes shadow {
    0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
    50% { transform: translateX(-50%) scale(0.8); opacity: 0.1; }
}

/* Button hover effect */
.btn-hover {
    animation: buttonPulse 0.3s ease;
}

@keyframes buttonPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cart-item-image {
        width: 40px !important;
        height: 40px !important;
    }

    .quantity-controls {
        flex-direction: column;
        height: auto;
    }

    .quantity-value {
        margin: 5px 0;
    }

    .cart-actions {
        flex-direction: column;
        gap: 15px;
    }

    .action-buttons {
        display: flex;
        width: 100%;
    }

    .action-buttons .btn {
        flex: 1;
    }
}
</style>
@endpush
@endsection
