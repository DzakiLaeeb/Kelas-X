<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$conn = getConnection();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - TokoOnline</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #2c3e50;
            --primary-color: #3498db;
            --secondary-color: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
        }

        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #f8f9fa;
            --primary-color: #2980b9;
            --secondary-color: #2c3e50;
            --card-bg: #2c3e50;
            --border-color: #4a5568;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--secondary-color);
            border-color: var(--border-color);
            font-weight: 600;
        }

        .settings-icon {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2573a7;
            border-color: #2573a7;
        }

        .settings-nav {
            position: sticky;
            top: 20px;
        }

        .settings-nav .nav-link {
            color: var(--text-color);
            border-radius: 0.25rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
        }

        .settings-nav .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .settings-nav .nav-link:hover:not(.active) {
            background-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white py-3 mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0">TokoOnline</h1>
                <a href="index.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="settings-nav">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-cogs settings-icon"></i>Menu Pengaturan
                        </div>
                        <div class="card-body p-0">
                            <nav class="nav flex-column">
                                <a class="nav-link active" href="#appearance"><i class="fas fa-palette me-2"></i>Tampilan</a>
                                <a class="nav-link" href="#language"><i class="fas fa-language me-2"></i>Bahasa</a>
                                <a class="nav-link" href="#notifications"><i class="fas fa-bell me-2"></i>Notifikasi</a>
                                <a class="nav-link" href="#account"><i class="fas fa-user-cog me-2"></i>Akun</a>
                                <a class="nav-link" href="#privacy"><i class="fas fa-shield-alt me-2"></i>Privasi</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9">
                <h1 class="mb-4">PENGATURAN</h1>
                
                <!-- Appearance Settings -->
                <div id="appearance" class="card">
                    <div class="card-header">
                        <i class="fas fa-palette settings-icon"></i>Pengaturan Tampilan
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Mode Tampilan</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle">
                                <label class="form-check-label" for="darkModeToggle">Aktifkan Mode Gelap</label>
                            </div>
                            <small class="text-muted">Mode gelap mengurangi kelelahan mata saat malam hari.</small>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Ukuran Font</h5>
                            <div class="d-flex align-items-center">
                                <span class="me-2"><i class="fas fa-font fa-sm"></i></span>
                                <input type="range" class="form-range" min="80" max="120" value="100" id="fontSizeRange">
                                <span class="ms-2"><i class="fas fa-font fa-lg"></i></span>
                            </div>
                            <small class="text-muted">Sesuaikan ukuran font untuk kenyamanan membaca.</small>
                        </div>
                        
                        <div>
                            <h5>Tata Letak</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="layoutOption" id="layoutCompact" checked>
                                <label class="form-check-label" for="layoutCompact">
                                    Kompak
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="layoutOption" id="layoutComfortable">
                                <label class="form-check-label" for="layoutComfortable">
                                    Nyaman
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Language Settings -->
                <div id="language" class="card">
                    <div class="card-header">
                        <i class="fas fa-language settings-icon"></i>Pengaturan Bahasa
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="languageSelect" class="form-label">Pilih Bahasa</label>
                            <select class="form-select" id="languageSelect">
                                <option value="id" selected>Bahasa Indonesia</option>
                                <option value="en">English</option>
                                <option value="jw">Basa Jawa</option>
                                <option value="su">Basa Sunda</option>
                            </select>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoTranslate">
                            <label class="form-check-label" for="autoTranslate">Terjemahkan otomatis deskripsi produk</label>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Settings -->
                <div id="notifications" class="card">
                    <div class="card-header">
                        <i class="fas fa-bell settings-icon"></i>Pengaturan Notifikasi
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Email Notifikasi</h5>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailOrderUpdates" checked>
                                <label class="form-check-label" for="emailOrderUpdates">Pembaruan pesanan</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailPromo" checked>
                                <label class="form-check-label" for="emailPromo">Promosi dan diskon</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailNewsletter">
                                <label class="form-check-label" for="emailNewsletter">Newsletter bulanan</label>
                            </div>
                        </div>
                        
                        <div>
                            <h5>Notifikasi Browser</h5>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="browserNotif">
                                <label class="form-check-label" for="browserNotif">Aktifkan notifikasi browser</label>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" id="testNotification">
                                <i class="fas fa-bell me-1"></i> Uji Notifikasi
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Account Settings -->
                <div id="account" class="card">
                    <div class="card-header">
                        <i class="fas fa-user-cog settings-icon"></i>Pengaturan Akun
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Informasi Profil</h5>
                            <div class="mb-3">
                                <label for="profileName" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="profileName" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profileEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="profileEmail" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profilePhone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="profilePhone">
                            </div>
                        </div>
                        
                        <div>
                            <h5>Keamanan</h5>
                            <button class="btn btn-outline-primary mb-2">
                                <i class="fas fa-key me-1"></i> Ubah Kata Sandi
                            </button>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                <label class="form-check-label" for="twoFactorAuth">Aktifkan Autentikasi Dua Faktor</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Privacy Settings -->
                <div id="privacy" class="card">
                    <div class="card-header">
                        <i class="fas fa-shield-alt settings-icon"></i>Pengaturan Privasi
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Data dan Privasi</h5>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="dataSharingConsent" checked>
                                <label class="form-check-label" for="dataSharingConsent">Izinkan berbagi data untuk meningkatkan layanan</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="cookieConsent" checked>
                                <label class="form-check-label" for="cookieConsent">Terima cookie untuk pengalaman yang lebih baik</label>
                            </div>
                        </div>
                        
                        <div>
                            <h5>Riwayat Aktivitas</h5>
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Riwayat Pencarian
                            </button>
                            <button class="btn btn-outline-danger btn-sm ms-2">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Riwayat Produk Dilihat
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button class="btn btn-secondary me-2">Batal</button>
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">© 2023 TokoOnline. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        // Dark mode functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Check for saved theme
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            body.setAttribute('data-theme', 'dark');
            darkModeToggle.checked = true;
        }

        // Toggle dark mode
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                body.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                body.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>
