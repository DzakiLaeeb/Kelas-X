@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    :root {
        --primary-color: #3498db;
        --primary-hover: #2980b9;
        --secondary-color: #e3f2fd;
        --text-color: #2c3e50;
        --light-text: #7f8c8d;
        --card-bg: #ffffff;
        --bg-light: #f8f9fa;
        --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 10px 15px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 15px 25px rgba(0, 0, 0, 0.15);
        --border-radius-sm: 8px;
        --border-radius-md: 16px;
        --border-radius-lg: 24px;
    }

    .login-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: linear-gradient(135deg, #f0f8ff 0%, #e3f2fd 100%);
        position: relative;
        overflow: hidden;
    }

    .login-section::before {
        content: '';
        position: absolute;
        top: -50px;
        left: -50px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(52, 152, 219, 0.1);
        z-index: 0;
    }

    .login-section::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(52, 152, 219, 0.1);
        z-index: 0;
    }

    .login-container {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 3rem;
        max-width: 500px;
        width: 100%;
        position: relative;
        z-index: 1;
        animation: fadeIn 0.5s ease;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .login-logo {
        width: 80px;
        height: 80px;
        margin-bottom: 1.5rem;
    }

    .login-header h2 {
        color: var(--primary-color);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-header p {
        color: var(--light-text);
        font-size: 1.1rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.75rem;
        color: var(--text-color);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-group .input-icon {
        position: absolute;
        left: 1rem;
        top: 3.1rem;
        color: var(--light-text);
    }

    .form-group input {
        width: 100%;
        padding: 1rem 1rem 1rem 2.75rem;
        border: 2px solid #e0e0e0;
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f9fbfd;
    }

    .form-group input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        outline: none;
        background-color: white;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        margin-right: 0.5rem;
        width: 18px;
        height: 18px;
    }

    .form-check-label {
        color: var(--light-text);
        font-size: 0.95rem;
    }

    .forgot-password {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        float: right;
    }

    .forgot-password:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .login-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        border: none;
        border-radius: var(--border-radius-sm);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
    }

    .register-link {
        text-align: center;
        margin-top: 2rem;
        color: var(--light-text);
        font-size: 1rem;
    }

    .register-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .register-link a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .alert {
        padding: 1rem;
        border-radius: var(--border-radius-sm);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .social-login {
        margin-top: 2rem;
        text-align: center;
    }

    .social-login p {
        color: var(--light-text);
        margin-bottom: 1rem;
        position: relative;
    }

    .social-login p::before,
    .social-login p::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background-color: #e0e0e0;
    }

    .social-login p::before {
        left: 0;
    }

    .social-login p::after {
        right: 0;
    }

    .social-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: white;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .social-btn i {
        font-size: 1.25rem;
    }

    .social-btn.google i {
        color: #DB4437;
    }

    .social-btn.facebook i {
        color: #4267B2;
    }

    .social-btn.twitter i {
        color: #1DA1F2;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 576px) {
        .login-container {
            padding: 2rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
        }

        .login-header p {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="login-section">
    <div class="login-container">
        <div class="login-header">
            <div class="text-center mb-4">
                <img src="https://www.shutterstock.com/image-vector/atk-letter-design-technology-logo-260nw-2384732247.jpg" alt="Logo" class="login-logo img-fluid rounded-circle">
            </div>
            <h2>Selamat Datang</h2>
            <p>Silakan masuk untuk melanjutkan</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <a href="#" class="forgot-password">Lupa password?</a>
            </div>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <p class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar disini</a>
        </p>

        <div class="social-login">
            <p>Atau masuk dengan</p>
            <div class="social-buttons">
                <button class="social-btn google">
                    <i class="fab fa-google"></i>
                </button>
                <button class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="social-btn twitter">
                    <i class="fab fa-twitter"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
