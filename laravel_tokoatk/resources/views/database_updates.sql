-- Add category_id, description, and weight to products table
ALTER TABLE products
ADD COLUMN category_id INT DEFAULT 1,
ADD COLUMN description TEXT,
ADD COLUMN weight INT DEFAULT 100;

-- Add stock column to products table
ALTER TABLE products
ADD COLUMN stock INT DEFAULT 0;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some default categories
INSERT INTO categories (name) VALUES 
('Alat Tulis'),
('Kertas'),
('Buku'),
('Perlengkapan Kantor');

-- Update existing products with random categories (1-4)
UPDATE products SET category_id = FLOOR(1 + RAND() * 4);

-- Update existing products with some default stock
UPDATE products SET stock = 100;
