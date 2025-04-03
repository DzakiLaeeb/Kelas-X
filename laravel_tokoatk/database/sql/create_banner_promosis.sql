-- Drop existing table if it exists
DROP TABLE IF EXISTS banner_promosi;
DROP TABLE IF EXISTS banner_promosis;

-- Create the table with Laravel's pluralized name
CREATE TABLE banner_promosis (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add some sample data
INSERT INTO banner_promosis (title, image_url, link, price) VALUES 
('Promo Alat Tulis', 'assets/promotions/promo1.jpg', '/shop/stationery', 50000.00),
('Diskon Buku', 'assets/promotions/promo2.jpg', '/shop/books', 75000.00),
('Special Offer Kertas', 'assets/promotions/promo3.jpg', '/shop/paper', 100000.00);

-- Create index for better performance
CREATE INDEX idx_banner_promosis_created_at ON banner_promosis(created_at);