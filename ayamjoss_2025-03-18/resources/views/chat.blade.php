@extends('layouts.app')

@section('content')
<div class="chat-container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="chat-title">Live Chat</h2>
            <p class="chat-subtitle">Hubungi kami langsung melalui aplikasi chat favorit Anda</p>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <!-- WhatsApp Card -->
        <div class="col-md-5 mb-4">
            <div class="chat-card whatsapp-card">
                <div class="chat-card-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="chat-card-content">
                    <h3>WhatsApp</h3>
                    <p>Layanan cepat untuk pertanyaan dan pesanan</p>
                    <ul class="chat-features">
                        <li><i class="fas fa-check-circle"></i> Respon cepat dalam 5 menit</li>
                        <li><i class="fas fa-check-circle"></i> Kirim foto pesanan Anda</li>
                        <li><i class="fas fa-check-circle"></i> Lacak status pesanan</li>
                    </ul>
                    <a href="https://wa.me/628123456789" class="chat-button whatsapp-button" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Telegram Card -->
        <div class="col-md-5 mb-4">
            <div class="chat-card telegram-card">
                <div class="chat-card-icon">
                    <i class="fab fa-telegram-plane"></i>
                </div>
                <div class="chat-card-content">
                    <h3>Telegram</h3>
                    <p>Layanan chat untuk informasi dan promosi</p>
                    <ul class="chat-features">
                        <li><i class="fas fa-check-circle"></i> Dapatkan katalog menu digital</li>
                        <li><i class="fas fa-check-circle"></i> Info promo terbaru</li>
                        <li><i class="fas fa-check-circle"></i> Bergabung dengan grup komunitas</li>
                    </ul>
                    <a href="https://t.me/ayamgorengjoss" class="chat-button telegram-button" target="_blank">
                        <i class="fab fa-telegram-plane"></i> Chat via Telegram
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Service Hours -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="cs-hours-card">
                <div class="cs-hours-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="cs-hours-content">
                    <h3>Jam Layanan Customer Service</h3>
                    <div class="cs-hours-grid">
                        <div class="cs-day">
                            <h4>Senin - Jumat</h4>
                            <p>09:00 - 21:00 WIB</p>
                        </div>
                        <div class="cs-day">
                            <h4>Sabtu - Minggu</h4>
                            <p>10:00 - 22:00 WIB</p>
                        </div>
                    </div>
                    <p class="cs-note"><i class="fas fa-info-circle"></i> Di luar jam operasional, pesan Anda akan dibalas pada hari kerja berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container {
    padding: 2rem 0;
}

.chat-title {
    color: var(--primary-red);
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.chat-title:after {
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

.chat-subtitle {
    color: var(--text-color);
    font-size: 1.1rem;
    margin-top: 20px;
    margin-bottom: 30px;
}

.chat-card {
    background-color: var(--card-bg);
    border-radius: 15px;
    padding: 25px;
    box-shadow: var(--card-shadow);
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.chat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
}

.whatsapp-card {
    border-left: 5px solid #25D366;
}

.telegram-card {
    border-left: 5px solid #0088cc;
}

.chat-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    font-size: 2rem;
}

.whatsapp-card .chat-card-icon {
    background-color: #25D366;
    color: white;
}

.telegram-card .chat-card-icon {
    background-color: #0088cc;
    color: white;
}

.chat-card-content h3 {
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.5rem;
    color: var(--text-color);
}

.chat-card-content p {
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.chat-features {
    list-style: none;
    padding: 0;
    margin-bottom: 25px;
}

.chat-features li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    color: var(--text-color);
}

.chat-features li i {
    margin-right: 10px;
}

.whatsapp-card .chat-features li i {
    color: #25D366;
}

.telegram-card .chat-features li i {
    color: #0088cc;
}

.chat-button {
    display: inline-flex;
    align-items: center;
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.chat-button i {
    margin-right: 8px;
    font-size: 1.2rem;
}

.whatsapp-button {
    background-color: #25D366;
    color: white;
}

.telegram-button {
    background-color: #0088cc;
    color: white;
}

.whatsapp-button:hover {
    background-color: #128C7E;
    color: white;
    transform: translateY(-3px);
}

.telegram-button:hover {
    background-color: #0077b3;
    color: white;
    transform: translateY(-3px);
}

.cs-hours-card {
    background-color: var(--card-bg);
    border-radius: 15px;
    padding: 25px;
    box-shadow: var(--card-shadow);
    display: flex;
    align-items: flex-start;
    margin-top: 30px;
}

.cs-hours-icon {
    font-size: 2.5rem;
    color: var(--primary-red);
    margin-right: 20px;
}

.cs-hours-content h3 {
    color: var(--text-color);
    font-weight: 600;
    margin-bottom: 20px;
}

.cs-hours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.cs-day h4 {
    color: var(--text-color);
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.cs-day p {
    color: var(--text-secondary);
    margin: 0;
}

.cs-note {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
}

.cs-note i {
    color: var(--primary-yellow);
    margin-right: 5px;
}

/* Dark Mode Adjustments */
[data-bs-theme="dark"] .chat-card,
[data-bs-theme="dark"] .cs-hours-card {
    background-color: var(--card-bg);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .cs-hours-card {
        flex-direction: column;
        text-align: center;
    }
    
    .cs-hours-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .cs-hours-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>
@endsection
