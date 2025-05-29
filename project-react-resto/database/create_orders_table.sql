-- Create orders table for storing customer orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    nama_menu VARCHAR(255) NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_harga DECIMAL(10,2) NOT NULL,
    customer_name VARCHAR(255) NOT NULL DEFAULT 'Guest User',
    customer_phone VARCHAR(20) DEFAULT '',
    notes TEXT DEFAULT '',
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key constraint (optional, if you want to enforce referential integrity)
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE,
    
    -- Indexes for better performance
    INDEX idx_menu_id (menu_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_customer_name (customer_name)
);

-- Insert some sample orders for testing
INSERT INTO orders (menu_id, nama_menu, harga_satuan, quantity, total_harga, customer_name, customer_phone, notes, status) VALUES
(1, 'Nasi Goreng Spesial', 25000.00, 2, 50000.00, 'John Doe', '081234567890', 'Pedas sedang', 'pending'),
(2, 'Ayam Bakar', 30000.00, 1, 30000.00, 'Jane Smith', '081234567891', 'Tanpa sambal', 'confirmed'),
(3, 'Es Teh Manis', 8000.00, 3, 24000.00, 'Bob Wilson', '081234567892', '', 'ready');
