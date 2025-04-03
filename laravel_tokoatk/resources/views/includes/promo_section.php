<?php
require_once 'db_connection.php';

function getPromos($limit = 3) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, title, image_url, price, link FROM banner_promosi ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Promo query error: " . $e->getMessage());
        return [];
    }
}
?>

<style>
    .promo-container {
        max-width: 500px;
        margin: 4rem auto;
        padding: 0 2rem;
    }

    .promo-grid {
        gap: 2.5rem;
        display: flex; /* Add this line */
        flex-direction: row; /* Ensure horizontal layout */
        overflow-x: auto; /* Add horizontal scrolling if needed */
    }

    .promo-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .promo-link {
        text-decoration: none;
        color: inherit;
    }

    .promo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .promo-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .promo-card:hover .promo-image {
        transform: scale(1.05);
    }

    .promo-info {
        padding: 1.8rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
    }

    .promo-title {
        font-size: 1.3rem;
        margin-bottom: 0.8rem;
        color: #2c3e50;
    }

    .promo-price {
        color: #3498db;
        font-weight: 600;
        font-size: 1.2rem;
    }

    /* Dark mode compatibility */
    [data-theme="dark"] .promo-card {
        background: #2c3e50;
    }

    [data-theme="dark"] .promo-info {
        background: rgba(44, 62, 80, 0.95);
    }

    [data-theme="dark"] .promo-title {
        color: #ecf0f1;
    }

    [data-theme="dark"] .promo-price {
        color: #3498db;
    }
</style>

<?php

function renderPromoSection() {
    $promos = getPromos();
    ob_start();
    ?>
    <section class="promo-container">
        <div class="promo-grid">
            <?php if (!empty($promos)): ?>
                <?php foreach ($promos as $promo): ?>
                    <div class="promo-card">
                        <a href="<?php echo htmlspecialchars($promo['link']); ?>" class="promo-link">
                            <img src="<?php echo htmlspecialchars($promo['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($promo['title']); ?>" 
                                 class="promo-image"
                                 onerror="this.src='images/default-promo.png'">
                            <div class="promo-info">
                                <h3 class="promo-title"><?php echo htmlspecialchars($promo['title']); ?></h3>
                                <?php if ($promo['price']): ?>
                                    <p class="promo-price">Rp <?php echo number_format($promo['price'], 0, ',', '.'); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="promo-card">
                        <img src="images/<?php echo $i; ?>.png" alt="Promo <?php echo $i; ?>" class="promo-image">
                        <div class="promo-info">
                            <h3 class="promo-title">Promo <?php echo $i; ?></h3>
                            <p class="promo-price">Rp <?php echo number_format(100000 * $i, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
