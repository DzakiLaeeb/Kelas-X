@extends('layouts.app')

<style>
:root {
    /* Light mode variables */
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --text-primary: #333333;
    --text-secondary: #6c757d;
    --primary-red: #e63946;
    --primary-red-hover: #d32f2f;
    --primary-yellow: #f1c40f;
    --border-color: #eaeaea;
    --input-bg: #ffffff;
    --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    --form-gradient: linear-gradient(135deg, #fff8dc 0%, #ffffff 100%);
    --input-placeholder: #a0a0a0;
    --toggle-bg: #f0f0f0;
    --toggle-circle: #e63946;
    --register-card-bg: #ffffff;
}

[data-bs-theme="dark"] {
    /* Dark mode variables */
    --bg-primary: #121212;
    --bg-secondary: #1e1e1e;
    --text-primary: #f0f0f0;
    --text-secondary: #b0b0b0;
    --primary-red: #ff5b69;
    --primary-red-hover: #ff7070;
    --primary-yellow: #ffd54f;
    --border-color: #2c2c2c;
    --input-bg: #2c2c2c;
    --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    --form-gradient: linear-gradient(135deg, #1a1a1a 0%, #121212 100%);
    --input-placeholder: #707070;
    --toggle-bg: #333333;
    --toggle-circle: #ff5b69;
    --register-card-bg: #1e1e1e;
}

.fullscreen-form {
    min-height: calc(100vh - 150px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-primary);
    padding: 2rem 1rem;
    transition: all 0.3s ease;
}

.register-container {
    width: 100%;
    max-width: 600px;
    margin: auto;
}

.register-card {
    background: var(--register-card-bg);
    border: none !important;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
}

.register-header {
    background: var(--primary-red) !important;
    color: white;
    padding: 2rem !important;
    text-align: center;
    position: relative;
}

.register-header h4 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.register-header::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%) rotate(45deg);
    width: 30px;
    height: 30px;
    background: var(--primary-red);
}

.register-body {
    padding: 2.5rem 2rem !important;
    background: var(--register-card-bg);
    position: relative;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.form-control {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    background-color: var(--input-bg);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-control::placeholder {
    color: var(--input-placeholder);
}

.form-control:focus {
    border-color: var(--primary-yellow);
    box-shadow: 0 0 0 0.2rem rgba(255, 183, 3, 0.25);
    background-color: var(--input-bg);
    color: var(--text-primary);
}

textarea.form-control {
    min-height: 100px;
}

.btn-register {
    height: 50px;
    background: var(--primary-red);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.btn-register:hover {
    background: var(--primary-red-hover);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(214, 51, 51, 0.3);
}

.register-footer {
    text-align: center;
    margin-top: 2rem;
    color: var(--text-secondary);
    transition: color 0.3s ease;
}

.register-footer a {
    color: var(--primary-red);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.register-footer a:hover {
    color: var(--primary-red-hover);
    text-decoration: underline;
}

.brand-logo {
    text-align: center;
    margin-bottom: 2rem;
}

.brand-logo i {
    font-size: 3rem;
    color: var(--primary-red);
    transition: color 0.3s ease;
}

.alert {
    border-radius: 8px;
    margin-bottom: 1.5rem;
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .register-container {
        max-width: 100%;
        padding: 1rem;
    }
    
    .register-header h4 {
        font-size: 1.75rem;
    }
    
    .register-body {
        padding: 2rem 1.5rem !important;
    }
}
</style>

@section('content')
<div class="fullscreen-form">
    <div class="register-container">
        <div class="brand-logo">
            <i class="fas fa-drumstick-bite"></i>
        </div>
        
        <div class="register-card">
            <div class="register-header">
                <h4>Daftar Akun</h4>
            </div>
            
            <div class="register-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama lengkap Anda">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan email Anda">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Konfirmasi password">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  placeholder="Masukkan alamat lengkap Anda">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telp" class="form-label">Nomor Telepon</label>
                        <input type="text" 
                               class="form-control @error('telp') is-invalid @enderror" 
                               id="telp" 
                               name="telp" 
                               value="{{ old('telp') }}"
                               placeholder="Masukkan nomor telepon Anda">
                        @error('telp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-register w-100">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>
                </form>

                <div class="register-footer">
                    Sudah punya akun? 
                    <a href="{{ url('/login') }}">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply theme from localStorage
    function applyTheme() {
        const darkMode = localStorage.getItem('darkMode');
        if (darkMode === 'enabled') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-bs-theme');
        }
    }
    
    // Initial theme application
    applyTheme();
    
    // Listen for theme changes
    window.addEventListener('storage', function(e) {
        if (e.key === 'darkMode') {
            applyTheme();
        }
    });
    
    // Add subtle animation to form elements
    const formElements = document.querySelectorAll('.form-control, .btn-register');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
    });
});
</script>
@endsection


