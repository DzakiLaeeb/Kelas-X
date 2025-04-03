@extends('layouts.app')

@section('content')
<div class="contact-container">
    <div class="row">
        <div class="col-md-6">
            <div class="contact-info-card">
                <h2 class="section-title">Hubungi Kami</h2>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Alamat</h4>
                        <p>Jl. Raya Contoh No. 123, Kota, Indonesia</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Telepon</h4>
                        <p>+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>info@ayamgorengjoss.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Jam Operasional</h4>
                        <p>Senin - Minggu: 10:00 - 22:00</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="contact-form-card">
                <h2 class="section-title">Kirim Pesan</h2>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('kontak.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                               id="subject" name="subject" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-send">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.contact-container {
    padding: 2rem 0;
}

.section-title {
    color: var(--primary-red);
    font-weight: 700;
    margin-bottom: 1.5rem;
    position: relative;
}

.contact-info-card,
.contact-form-card {
    background-color: var(--card-bg);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--card-shadow);
    height: 100%;
    transition: all 0.3s ease;
}

.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: var(--bg-color);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--card-shadow-hover);
}

.info-item i {
    font-size: 1.5rem;
    color: var(--primary-red);
    margin-right: 1rem;
    margin-top: 0.2rem;
}

.info-item h4 {
    color: var(--text-color);
    font-size: 1.1rem;
    margin-bottom: 0.3rem;
}

.info-item p {
    color: var(--text-secondary);
    margin-bottom: 0;
}

.form-label {
    color: var(--text-color);
    font-weight: 500;
}

.form-control {
    background-color: var(--bg-color);
    border: 1px solid var(--text-secondary);
    color: var(--text-color);
    padding: 0.8rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-red);
    box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    background-color: var(--bg-color);
    color: var(--text-color);
}

.btn-send {
    background-color: var(--primary-red);
    color: white;
    padding: 0.8rem 2rem;
    border-radius: 30px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-send:hover {
    background-color: var(--primary-yellow);
    transform: translateY(-2px);
    color: var(--dark-brown);
}

/* Dark Mode Styles */
[data-bs-theme="dark"] .contact-info-card,
[data-bs-theme="dark"] .contact-form-card {
    background-color: var(--card-bg);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

[data-bs-theme="dark"] .info-item {
    background-color: #282828;
}

[data-bs-theme="dark"] .form-control {
    background-color: #282828;
    border-color: #404040;
    color: #e0e0e0;
}

[data-bs-theme="dark"] .form-control:focus {
    background-color: #282828;
    border-color: var(--primary-red);
    color: #e0e0e0;
}

[data-bs-theme="dark"] .form-control::placeholder {
    color: #808080;
}

[data-bs-theme="dark"] .info-item:hover {
    background-color: #333333;
}

[data-bs-theme="dark"] .btn-send {
    background-color: var(--primary-red);
    color: white;
}

[data-bs-theme="dark"] .btn-send:hover {
    background-color: var(--primary-yellow);
    color: #121212;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .contact-info-card,
    .contact-form-card {
        margin-bottom: 2rem;
    }
    
    .info-item {
        padding: 0.8rem;
    }
}

/* Add these to your existing styles */
.alert {
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    border-color: #198754;
    color: #198754;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border-color: #dc3545;
    color: #dc3545;
}

[data-bs-theme="dark"] .alert-success {
    background-color: rgba(25, 135, 84, 0.2);
    color: #75b798;
}

[data-bs-theme="dark"] .alert-danger {
    background-color: rgba(220, 53, 69, 0.2);
    color: #ea868f;
}

.alert ul {
    padding-left: 1rem;
}
</style>
@endsection
