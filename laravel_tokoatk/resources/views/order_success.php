<?php
session_start();
require_once __DIR__ . '/config/connection.php';

$order_id = $_GET['order'] ?? '';

if (empty($order_id) || !isset($_SESSION['cart_total'])) {
    header('Location: shop.php');
    exit;
}

// Use the total from session instead of recalculating
$total_amount = $_SESSION['cart_total'];

// Fetch order details for display
$stmt = $conn->prepare("
    SELECT o.*, p.name as product_name 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.session_id = ?
");
$stmt->execute([$order_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($orders)) {
    header('Location: shop.php');
    exit;
}

// Clear the cart total from session after displaying
$total_to_display = $total_amount;
unset($_SESSION['cart_total']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - TokoATK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-primary: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            font-family: var(--font-primary);
        }

        .success-container {
            max-width: 600px;
            margin: 5rem auto;
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            font-size: 5rem;
            color: #22c55e;
            margin-bottom: 1.5rem;
            animation: scaleIn 0.5s ease;
        }

        .success-title {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .order-number {
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .total-amount {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color:rgb(50, 146, 255);
            margin: 1.5rem 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.2rem 3rem;
            background:rgb(50, 146, 255);
            color: white;
            text-decoration: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px  rgb(50, 146, 255);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px  rgb(48, 140, 245);
        }

        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .order-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: left;
        }

        .order-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .item-quantity {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .item-subtotal {
            color: #2563eb;
            font-weight: 600;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1 class="success-title">Pesanan Berhasil!</h1>
        <p>Terima kasih telah berbelanja di TokoATK</p>
        <div class="order-number">
            No. Invoice: <strong><?= htmlspecialchars($order_id) ?></strong>
        </div>

        <p class="total-amount">Total Pembayaran: <strong>Rp <?= number_format($total_to_display, 0, ',', '.') ?></strong></p>
        <a href="shop.php" class="back-btn">
            <i class="fas fa-store"></i> Lanjutkan Belanja
        </a>
    </div>
</body>
</html>
