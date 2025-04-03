<?php
require_once '../db_connection.php';

function createTables() {
    try {
        $conn = getConnection();
        
        // Check if users table exists
        if (!tableExists('users')) {
            $conn->exec("
                CREATE TABLE users (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    email_verified_at TIMESTAMP NULL,
                    remember_token VARCHAR(100) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // Check if password_reset_tokens table exists
        if (!tableExists('password_reset_tokens')) {
            $conn->exec("
                CREATE TABLE password_reset_tokens (
                    email VARCHAR(100) PRIMARY KEY,
                    token VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // Check if sessions table exists
        if (!tableExists('sessions')) {
            $conn->exec("
                CREATE TABLE sessions (
                    id VARCHAR(255) PRIMARY KEY,
                    user_id BIGINT UNSIGNED NULL,
                    ip_address VARCHAR(45) NULL,
                    user_agent TEXT NULL,
                    payload LONGTEXT NOT NULL,
                    last_activity INT NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            
            $conn->exec("CREATE INDEX sessions_user_id_index ON sessions(user_id)");
            $conn->exec("CREATE INDEX sessions_last_activity_index ON sessions(last_activity)");
        }

        return true;
    } catch (PDOException $e) {
        error_log("Error creating tables: " . $e->getMessage());
        return false;
    }
}

// Call this function when setting up the application
if (!createTables()) {
    die("Failed to create necessary database tables.");
}
?>