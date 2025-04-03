
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Title with Enhanced Animation -->
            <div class="text-center mb-5 fade-in-down">
                <div class="title-badge mb-3 pulse-slow">
                    <i class="fas fa-drumstick-bite fa-2x"></i>
                </div>
                <h2 class="display-5 fw-bold text-gradient-red mb-3">Form Pemesanan</h2>
                <p class="lead text-muted">Lengkapi informasi di bawah untuk menikmati kelezatan Ayam Goreng Joss!</p>
                <div class="decoration-line mx-auto my-4"></div>
            </div>
            
            <div class="row g-4">
                <!-- Order Form with Enhanced Style -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden slide-up-card">
                        <div class="card-body p-0">
                            <!-- Progress Indicator -->
                            <div class="gradient-red-header px-4 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 badge-wobble">
                                        <i class="fas fa-pencil-alt me-1"></i> Langkah 1 dari 2
                                    </span>
                                    <small class="text-white">Informasi Pengiriman</small>
                                </div>
                            </div>
                            
                            <!-- Form Content -->
                            <div class="p-4 p-lg-5">
                                <form method="POST" action="{{ route('order') }}" class="needs-validation floating-labels" novalidate>
                                    @csrf
                                    
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-floating mb-1 input-group-hover">
                                                <input type="text" id="name" name="name" 
                                                    class="form-control border-warning-subtle @error('name') is-invalid @enderror" 
                                                    placeholder="Nama Lengkap" 
                                                    value="{{ old('name') }}" required>
                                                <label for="name">
                                                    <i class="fas fa-user text-warning me-1"></i> Nama Lengkap
                                                </label>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text ms-2"><i class="fas fa-info-circle text-primary me-1"></i>Sesuai dengan kartu identitas Anda</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-floating mb-1 input-group-hover">
                                                <textarea id="address" name="address" 
                                                    class="form-control border-warning-subtle @error('address') is-invalid @enderror" 
                                                    placeholder="Alamat Pengiriman" 
                                                    style="height: 100px" required>{{ old('address') }}</textarea>
                                                <label for="address">
                                                    <i class="fas fa-map-marker-alt text-warning me-1"></i> Alamat Pengiriman
                                                </label>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text ms-2"><i class="fas fa-info-circle text-primary me-1"></i>Sertakan RT/RW, kelurahan, kecamatan, dan kode pos</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label mb-2">
                                                <i class="fas fa-phone-alt text-warning me-1"></i> Nomor Telepon
                                            </label>
                                            <div class="input-group input-group-lg mb-1 input-group-hover">
                                                <span class="input-group-text bg-light border-warning-subtle border-end-0">+62</span>
                                                <input type="tel" id="phone" name="phone" 
                                                    class="form-control border-warning-subtle border-start-0 @error('phone') is-invalid @enderror" 
                                                    placeholder="8123456789" 
                                                    value="{{ old('phone') }}" required>
                                                <button class="btn btn-outline-warning" type="button" data-bs-toggle="tooltip" 
                                                    title="Nomor tanpa angka 0 di depan">
                                                    <i class="fas fa-question-circle"></i>
                                                </button>
                                            </div>
                                            @error('phone')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text ms-2"><i class="fas fa-info-circle text-primary me-1"></i>Pastikan nomor aktif untuk konfirmasi pesanan</div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4 gradient-divider-red">
                                    
                                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-3">
                                        <a href="{{ route('home') }}" class="btn btn-light btn-lg px-4 d-flex align-items-center btn-hover-lift shadow-sm">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            <span>Kembali</span>
                                        </a>
                                        <button type="submit" class="btn btn-gradient-red btn-lg px-5 d-flex align-items-center justify-content-center btn-hover-lift shadow">
                                            <span>Lanjutkan</span>
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary with Enhanced Design -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg rounded-4 sticky-md-top order-summary-card" style="top: 2rem;">
                        <div class="card-header gradient-red-header text-white p-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-shopping-cart me-2"></i>Ringkasan Pesanan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Food illustration -->
                            <div class="text-center mb-4 food-illustration">
                                <img src="https://cdn-icons-png.flaticon.com/512/1046/1046751.png" alt="Ayam Goreng" class="img-fluid" style="max-width: 80px;">
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3 price-item">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold">Rp 250.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 price-item">
                                <span class="text-muted">Pengiriman</span>
                                <span class="fw-bold">Rp 15.000</span>
                            </div>
                            <hr class="gradient-divider-red">
                            <div class="d-flex justify-content-between mb-3 price-total">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold text-gradient-red fs-5">Rp 265.000</span>
                            </div>
                            
                            <div class="alert bg-gradient-cream border-0 shadow-sm mt-4 mb-0">
                                <div class="d-flex">
                                    <div class="me-3 text-danger">
                                        <i class="fas fa-headset fs-4 floating"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Butuh bantuan?</h6>
                                        <p class="mb-0 small">Hubungi customer service kami di <strong>0812-3456-7890</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white p-3 text-center border-top">
                            <div class="payment-icons my-2">
                                <i class="fab fa-cc-visa mx-1 text-muted"></i>
                                <i class="fab fa-cc-mastercard mx-1 text-muted"></i>
                                <i class="fab fa-cc-paypal mx-1 text-muted"></i>
                            </div>
                            <div class="d-flex justify-content-center gap-2 secure-payment">
                                <i class="fas fa-shield-alt text-success"></i>
                                <span class="small text-muted">Pembayaran aman & terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Features Section -->
            <div class="row mt-5 pt-4">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-4 text-center">
                        <div class="feature-item fade-in-up">
                            <div class="icon-wrapper gradient-cream rounded-circle p-3 mb-3 mx-auto floating">
                                <i class="fas fa-truck text-danger fs-4"></i>
                            </div>
                            <h6 class="text-primary-red">Pengiriman Cepat</h6>
                            <p class="text-muted small mb-0">Estimasi 30-45 menit ke alamat Anda</p>
                        </div>
                        
                        <div class="feature-item fade-in-up" style="animation-delay: 0.2s">
                            <div class="icon-wrapper gradient-cream rounded-circle p-3 mb-3 mx-auto floating">
                                <i class="fas fa-undo-alt text-danger fs-4"></i>
                            </div>
                            <h6 class="text-primary-red">Garansi Kepuasan</h6>
                            <p class="text-muted small mb-0">Tidak puas? Kami ganti dengan yang baru</p>
                        </div>
                        
                        <div class="feature-item fade-in-up" style="animation-delay: 0.4s">
                            <div class="icon-wrapper gradient-cream rounded-circle p-3 mb-3 mx-auto floating">
                                <i class="fas fa-drumstick-bite text-danger fs-4"></i>
                            </div>
                            <h6 class="text-primary-red">Resep Rahasia</h6>
                            <p class="text-muted small mb-0">Rempah pilihan khas Ayam Goreng Joss</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Review -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 review-card">
                        <div class="card-body text-center p-4">
                            <div class="quotation-mark">"</div>
                            <p class="lead fst-italic mb-3">Ayam Goreng Joss adalah yang terbaik! Rasanya juara dan pengirimannya cepat. Pasti pesan lagi!</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="review-avatar me-3">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer" class="rounded-circle" width="50">
                                </div>
                                <div class="text-start">
                                    <h6 class="mb-0">Budi Santoso</h6>
                                    <div class="text-warning">
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
    </div>
</div>

@push('styles')
<style>
    /* Theme Colors and Gradients - Using Ayam Goreng Joss theme */
    :root {
        --primary-red: #e63946;
        --primary-yellow: #ffb703;
        --secondary-yellow: #ffd166;
        --dark-brown: #5c3c10;
        --light-cream: #fff1e6;
    }
    
    /* Gradient Backgrounds */
    .gradient-red-header {
        background: linear-gradient(45deg, var(--primary-red), #ff6b6b);
    }
    
    .gradient-cream {
        background: linear-gradient(45deg, var(--light-cream), #fff9f0);
    }
    
    .text-gradient-red {
        background: linear-gradient(45deg, var(--primary-red), #ff6b6b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .text-primary-red {
        color: var(--primary-red);
    }
    
    .btn-gradient-red {
        background: linear-gradient(45deg, var(--primary-red), #ff6b6b);
        border: none;
        color: white;
    }
    
    .btn-gradient-red:hover {
        background: linear-gradient(45deg, #d62f3c, #ff5252);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3);
    }
    
    .gradient-divider-red {
        height: 2px;
        background: linear-gradient(to right, var(--primary-red), #ff6b6b);
        border: none;
    }
    
    .bg-gradient-cream {
        background: linear-gradient(45deg, var(--light-cream), #fff9f0);
    }
    
    /* Enhanced Animations */
    .fade-in-down {
        animation: fadeInDown 0.8s ease-in-out;
    }
    
    .fade-in-up {
        animation: fadeInUp 0.8s ease-in-out;
    }
    
    .slide-up-card {
        animation: slideUpCard 0.6s ease-out;
    }
    
    .order-summary-card {
        animation: slideUpCard 0.6s ease-out 0.3s both;
    }
    
    .floating {
        animation: floating 3s ease-in-out infinite;
    }
    
    .pulse-slow {
        animation: pulseSlow 3s infinite;
    }
    
    .badge-wobble {
        animation: wobble 2s ease-in-out infinite;
        animation-delay: 1s;
    }
    
    @keyframes fadeInDown {
        from { 
            opacity: 0;
            transform: translateY(-30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUpCard {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes floating {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }
    
    @keyframes pulseSlow {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(230, 57, 70, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(230, 57, 70, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(230, 57, 70, 0); }
    }
    
    @keyframes wobble {
        0% { transform: translateX(0); }
        15% { transform: translateX(-5px) rotate(-5deg); }
        30% { transform: translateX(4px) rotate(4deg); }
        45% { transform: translateX(-3px) rotate(-2deg); }
        60% { transform: translateX(2px) rotate(1deg); }
        75% { transform: translateX(-1px) rotate(-1deg); }
        100% { transform: translateX(0); }
    }
    
    /* Enhanced Components */
    .card {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .shadow-lg {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .input-group-hover {
        transition: all 0.3s ease;
    }
    
    .input-group-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .btn-hover-lift {
        transition: all 0.3s ease;
    }
    
    .btn-hover-lift:hover {
        transform: translateY(-3px);
    }
    
    .title-badge {
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, var(--primary-red), #ff6b6b);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 20px rgba(230, 57, 70, 0.3);
    }
    
    .price-item, .price-total {
        transition: all 0.3s ease;
    }
    
    .price-item:hover {
        transform: translateX(5px);
    }
    
    .secure-payment {
        opacity: 0.8;
        transition: all 0.3s ease;
    }
    
    .secure-payment:hover {
        opacity: 1;
        transform: scale(1.05);
    }
    
    .feature-item {
        transition: all 0.3s ease;
        padding: 1.5rem;
        border-radius: 15px;
    }
    
    .feature-item:hover {
        transform: translateY(-5px);
        background-color: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    
    .icon-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    
    /* Form Styling */
    .form-control {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
        border-width: 2px;
    }
    
    .form-control:focus {
        box-shadow: 0 5px 15px rgba(230, 57, 70, 0.1);
        border-color: var(--primary-red);
    }
    
    .border-warning-subtle {
        border-color: #ffd166 !important;
    }
    
    .input-group-text {
        border-radius: 10px;
    }
    
    .floating-labels label {
        transition: all 0.3s ease;
    }
    
    .form-floating>.form-control:focus~label {
        color: var(--primary-red);
    }
    
    /* Food illustration animation */
    .food-illustration img {
        transition: all 0.3s ease;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    /* Decorative line */
    .decoration-line {
        height: 4px;
        width: 80px;
        background: linear-gradient(to right, var(--primary-red), #ff6b6b);
        border-radius: 2px;
    }
    
    /* Review card styling */
    .review-card {
        background-color: #fff9f0;
        overflow: hidden;
        position: relative;
        margin-top: 2rem;
        transition: all 0.3s ease;
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .quotation-mark {
        position: absolute;
        top: -10px;
        left: 10px;
        font-size: 120px;
        color: rgba(230, 57, 70, 0.1);
        font-family: Georgia, serif;
    }
    
    .review-avatar img {
        border: 3px solid white;
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }
    
    /* Payment icons styling */
    .payment-icons i {
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .payment-icons i:hover {
        color: var(--primary-red) !important;
        transform: scale(1.1);
    }
</style>
@endpush
@endsection
