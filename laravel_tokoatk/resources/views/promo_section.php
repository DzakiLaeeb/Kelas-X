<header>
        <div class="container">
            <div class="nav-container">
                <a href="index.php" class="logo">TokoATK</a>
                <nav class="nav-menu">
                    <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
                    <a href="shop.php"><i class="fas fa-shopping-cart"></i> Produk</a>
                    <a href="tentangkami.php"><i class="fas fa-info-circle"></i> Tentang Kami</a>
                    <a href="hubungikami.php"><i class="fas fa-envelope"></i> Hubungi Kami</a>
                </nav>

                <div class="auth-nav">
                    <a href="cart.php"><i class="fas fa-shopping-basket"></i>
                        <?php if(isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                            <span class="cart-count"><?php echo $_SESSION['cart_count']; ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle user-greeting" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Halo, <?= htmlspecialchars($_SESSION['username']) ?>!
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="orders.php"><i class="fas fa-shopping-bag me-2"></i>Pesanan</a></li>
                                <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="?logout=true"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <a href="login.php" class="btn">Masuk</a>
                        <a href="register.php" class="btn">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg-color: #ffffff;
    --text-color: #2c3e50;
    --primary-color: #3498db;
    --secondary-color: #f8f9fa;
    --header-bg: #ffffff;
    --footer-bg: #2c3e50;
}

[data-theme="dark"] {
    --bg-color: #1a1a1a;
    --text-color: #f8f9fa;
    --primary-color: #2980b9;
    --secondary-color: #2c3e50;
    --header-bg: #2c3e50;
    --footer-bg: #1a1a1a;
}

body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: var(--bg-color);

    /* Hide scrollbar for Chrome, Safari and Opera */
    &::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

/* Enhanced Header Styles */
header {
    background-color: var(--header-bg);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 1.5rem 0;
    transition: all 0.3s ease;
}

header.shrink {
    padding: 0.5rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.logo {
    font-size: 1.8rem;
    transition: all 0.3s ease;
}

header.shrink .logo {
    font-size: 1.4rem;
}

.nav-menu {
    transition: all 0.3s ease;
}

header.shrink .nav-menu {
    font-size: 0.95rem;
}

header.shrink .auth-nav .btn {
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
}

header.shrink .user-greeting {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.8rem;
    font-weight: 700;
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.logo:hover {
    color: #2980b9;
}

.nav-menu {
    display: flex;
    gap: 2.5rem;
    align-items: center;
    flex: 1;
    justify-content: center;
}

.auth-nav {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-left: auto;
}

.auth-nav .btn {
    margin: 0 0.25rem;
}


.user-greeting {
    font-weight: 600;
    color: var(--text-color);
    background: var(--secondary-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.user-greeting:hover {
    background: var(--primary-color);
    color: var(--bg-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

[data-theme="dark"] .user-greeting {
    background: var(--primary-color);
    color: var(--bg-color);
}

[data-theme="dark"] .user-greeting:hover {
background: var(--primary-color);
opacity: 0.9;
}

[data-theme="dark"] .dropdown-menu {
background-color: #2c3e50;
border: none;
}

[data-theme="dark"] .dropdown-item {
color: #f8f9fa;
}

[data-theme="dark"] .dropdown-item:hover {
background-color: #3498db;
color: #ffffff;
}




.nav-menu a {
    color: var(--text-color);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    padding-bottom: 5px;
}

.nav-menu a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.nav-menu a:hover {
    color: var(--primary-color);
}

.nav-menu a:hover::after {
    width: 100%;
}


.nav-menu a:hover {
    color: var(--primary-color);
}

.cart-count {
    background-color: #e74c3c;
    color: white;
    border-radius: 50%;
    padding: 0.2rem 0.5rem;
    font-size: 0.8rem;
    margin-left: 1px;
    position: relative;
    top: -15px;
}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousel with autoplay
        const myCarousel = new bootstrap.Carousel(document.getElementById('bannerCarousel'), {
            interval: 3000,  // Time in milliseconds between slides
            ride: 'carousel',
            pause: 'hover',  // Pause on mouse hover
            wrap: true,      // Continuous loop
            touch: true      // Enable touch swiping on mobile
        });

        // Optional: Add fade effect to transitions
        document.querySelectorAll('.carousel-item').forEach(item => {
            item.style.transition = 'transform .6s ease-in-out';
        });
    });
    </script>

        <script>
            // Dark mode functionality
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
            }

            // Initialize all toasts

            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
            // Show toasts
            toastList.forEach(toast => toast.show());
        </script>
        <script>
    // Add this after your existing scripts
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Check for saved theme preference
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            body.setAttribute('data-theme', currentTheme);
            if (currentTheme === 'dark') {
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        }

        // Toggle dark mode
        darkModeToggle.addEventListener('click', function() {
            if (body.getAttribute('data-theme') === 'dark') {
                body.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                body.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });
    });
    </script>
    <script>
    // Shrinking navbar functionality
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        const logo = document.querySelector('.logo');
        const navMenu = document.querySelector('.nav-menu');
        
        if (window.scrollY > 50) {
            header.classList.add('shrink');
        } else {
            header.classList.remove('shrink');
        }
    });
    </script>
    <script>
    $(document).ready(function(){
        $('#carouselExampleIndicators').carousel({
            interval: 3000,    // Time in milliseconds between slides (3 seconds)
            ride: 'carousel',  // Enable auto-play
            pause: 'hover',   // Pause on mouse hover
            wrap: true        // Enable continuous loop
        });
    });
    </script>

<link rel="icon" type="jpg" href="https://www.shutterstock.com/image-vector/atk-letter-design-technology-logo-260nw-2384732247.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">