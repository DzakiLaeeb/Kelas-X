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
    --login-card-bg: #ffffff;
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
    --login-card-bg: #1e1e1e;
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

.login-container {
    width: 100%;
    max-width: 450px;
    margin: auto;
}

.login-card {
    background: var(--login-card-bg);
    border-radius: 15px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    transition: all 0.3s ease;
}

.login-header {
    padding: 2rem;
    text-align: center;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.login-header h4 {
    margin: 0;
    font-weight: 700;
    color: var(--primary-red);
}

.login-body {
    padding: 2rem;
}

.form-label {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control {
    height: 50px;
    border-radius: 10px;
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

.btn-login {
    height: 50px;
    background: var(--primary-red);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    margin-top: 1rem;
    position: relative;
    overflow: hidden;
}

.btn-login:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s ease;
}

.btn-login:hover:before {
    left: 100%;
}

.btn-login:hover {
    background: var(--primary-red-hover);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(214, 51, 51, 0.3);
}

.login-footer {
    text-align: center;
    margin-top: 2rem;
    color: var(--text-secondary);
    transition: color 0.3s ease;
}

.login-footer a {
    color: var(--primary-red);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.login-footer a:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: var(--primary-red-hover);
    transition: width 0.3s ease;
}

.login-footer a:hover:after {
    width: 100%;
}

.login-footer a:hover {
    color: var(--primary-red-hover);
}

.brand-logo {
    text-align: center;
    margin-bottom: 2rem;
}

.brand-logo i {
    font-size: 3.5rem;
    color: var(--primary-red);
    transition: color 0.3s ease;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}

.alert {
    border-radius: 10px;
    margin-bottom: 1.5rem;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Input field animation */
.form-control:focus + .focus-border {
    width: 100%;
    transition: 0.4s;
}

.focus-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary-yellow);
    transition: 0.4s;
}

.input-wrapper {
    position: relative;
}

/* Password visibility toggle */
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary-red);
}

/* Animation for logo */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.brand-logo i {
    animation: pulse 2s infinite;
}

/* Dark mode toggle styles */
.theme-toggle-wrapper {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 100;
}

.theme-toggle {
    background: var(--toggle-bg);
    border-radius: 20px;
    padding: 5px;
    width: 60px;
    height: 30px;
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.toggle-circle {
    position: absolute;
    width: 22px;
    height: 22px;
    background: var(--toggle-circle);
    border-radius: 50%;
    left: 4px;
    transition: transform 0.3s ease;
}

[data-bs-theme="dark"] .toggle-circle {
    transform: translateX(30px);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .login-container {
        max-width: 100%;
    }
    
    .login-header h4 {
        font-size: 1.75rem;
    }
    
    .login-body {
        padding: 2rem 1.5rem !important;
    }
}
</style>

@section('content')
<div class="fullscreen-form">
    <div class="login-container">
        <div class="brand-logo">
            <i class="fas fa-drumstick-bite"></i>
        </div>
        
        <div class="login-card">
            <div class="login-header">
                <h4>Login</h4>
            </div>
            
            <div class="login-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>

                <div class="login-footer">
                    Belum punya akun? 
                    <a href="{{ url('/register') }}">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('password');
        
        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = passwordToggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            });
        }
        
        // Theme handling
        const applyTheme = () => {
            try {
                const darkMode = localStorage.getItem('darkMode');
                document.documentElement.setAttribute('data-bs-theme', 
                    darkMode === 'enabled' ? 'dark' : 'light'
                );
            } catch (e) {
                console.error('Theme application failed:', e);
            }
        };
        
        applyTheme();
        window.addEventListener('storage', (e) => {
            if (e.key === 'darkMode') {
                applyTheme();
            }
        });

        // Form animations
        const formElements = document.querySelectorAll('.form-control, .btn-login');
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




