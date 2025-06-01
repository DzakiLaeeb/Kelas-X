<?php
// Fix Login Issues - Comprehensive Solution
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Login Issues</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-tools me-2"></i>
                            Fix Login Issues - Comprehensive Solution
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <?php
                        echo "<div class='alert alert-info'>";
                        echo "<i class='bi bi-info-circle me-2'></i>";
                        echo "<strong>Troubleshooting Login Issues...</strong>";
                        echo "</div>";
                        
                        $steps = [];
                        $errors = [];
                        
                        // Step 1: Check database connection
                        try {
                            require_once 'api/config.php';
                            $conn = getConnection();
                            $steps[] = "✅ Database connection successful";
                            
                            // Step 2: Check/Create users table
                            $result = $conn->query("SHOW TABLES LIKE 'users'");
                            if ($result->num_rows == 0) {
                                // Create users table
                                $createTableSQL = "
                                CREATE TABLE users (
                                    id INT AUTO_INCREMENT PRIMARY KEY,
                                    name VARCHAR(255) NOT NULL,
                                    username VARCHAR(255) UNIQUE NOT NULL,
                                    email VARCHAR(255) UNIQUE NOT NULL,
                                    email_verified_at TIMESTAMP NULL DEFAULT NULL,
                                    password VARCHAR(255) NOT NULL,
                                    role ENUM('admin', 'user', 'koki', 'kasir') NOT NULL DEFAULT 'user',
                                    remember_token VARCHAR(100) DEFAULT NULL,
                                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    
                                    INDEX idx_email (email),
                                    INDEX idx_username (username),
                                    INDEX idx_role (role)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                                ";
                                
                                if ($conn->query($createTableSQL)) {
                                    $steps[] = "✅ Users table created successfully";
                                } else {
                                    $errors[] = "❌ Failed to create users table: " . $conn->error;
                                }
                            } else {
                                $steps[] = "✅ Users table exists";
                            }
                            
                            // Step 3: Create/Update default users
                            $defaultUsers = [
                                [
                                    'name' => 'Administrator',
                                    'username' => 'admin',
                                    'email' => 'admin@restaurant.com',
                                    'password' => 'admin123',
                                    'role' => 'admin'
                                ],
                                [
                                    'name' => 'Kasir Demo',
                                    'username' => 'kasir',
                                    'email' => 'kasir@restaurant.com',
                                    'password' => 'kasir123',
                                    'role' => 'kasir'
                                ],
                                [
                                    'name' => 'Koki Demo',
                                    'username' => 'koki',
                                    'email' => 'koki@restaurant.com',
                                    'password' => 'koki123',
                                    'role' => 'koki'
                                ],
                                [
                                    'name' => 'User Demo',
                                    'username' => 'user',
                                    'email' => 'user@restaurant.com',
                                    'password' => 'user123',
                                    'role' => 'user'
                                ]
                            ];
                            
                            $now = date('Y-m-d H:i:s');
                            
                            foreach ($defaultUsers as $user) {
                                $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                                
                                // Check if user exists
                                $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                                $checkStmt->bind_param("ss", $user['username'], $user['email']);
                                $checkStmt->execute();
                                $result = $checkStmt->get_result();
                                
                                if ($result->num_rows > 0) {
                                    // Update existing user
                                    $updateStmt = $conn->prepare("UPDATE users SET name = ?, password = ?, role = ?, updated_at = ? WHERE username = ? OR email = ?");
                                    $updateStmt->bind_param("ssssss", $user['name'], $hashedPassword, $user['role'], $now, $user['username'], $user['email']);
                                    
                                    if ($updateStmt->execute()) {
                                        $steps[] = "✅ Updated user: {$user['username']} ({$user['role']})";
                                    } else {
                                        $errors[] = "❌ Failed to update user {$user['username']}: " . $updateStmt->error;
                                    }
                                    $updateStmt->close();
                                } else {
                                    // Insert new user
                                    $insertStmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                    $insertStmt->bind_param("sssssss", $user['name'], $user['username'], $user['email'], $hashedPassword, $user['role'], $now, $now);
                                    
                                    if ($insertStmt->execute()) {
                                        $steps[] = "✅ Created user: {$user['username']} ({$user['role']})";
                                    } else {
                                        $errors[] = "❌ Failed to create user {$user['username']}: " . $insertStmt->error;
                                    }
                                    $insertStmt->close();
                                }
                                $checkStmt->close();
                            }
                            
                            // Step 4: Display current users
                            echo "<h5 class='mt-4'><i class='bi bi-people me-2'></i>Current Users in Database:</h5>";
                            $result = $conn->query("SELECT id, name, username, email, role, created_at FROM users ORDER BY id");
                            
                            if ($result->num_rows > 0) {
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-striped table-sm'>";
                                echo "<thead class='table-dark'>";
                                echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>";
                                echo "</thead><tbody>";
                                
                                while ($row = $result->fetch_assoc()) {
                                    $roleClass = [
                                        'admin' => 'bg-danger',
                                        'kasir' => 'bg-warning',
                                        'koki' => 'bg-info',
                                        'user' => 'bg-secondary'
                                    ];
                                    
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td><strong>{$row['username']}</strong></td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td><span class='badge {$roleClass[$row['role']]}'>{$row['role']}</span></td>";
                                    echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table>";
                                echo "</div>";
                                $steps[] = "✅ Found {$result->num_rows} users in database";
                            } else {
                                $errors[] = "❌ No users found in database";
                            }
                            
                            $conn->close();
                            
                        } catch (Exception $e) {
                            $errors[] = "❌ Database error: " . $e->getMessage();
                        }
                        
                        // Display results
                        if (!empty($steps)) {
                            echo "<h5 class='mt-4 text-success'><i class='bi bi-check-circle me-2'></i>Successful Steps:</h5>";
                            echo "<ul class='list-group'>";
                            foreach ($steps as $step) {
                                echo "<li class='list-group-item list-group-item-success'>{$step}</li>";
                            }
                            echo "</ul>";
                        }
                        
                        if (!empty($errors)) {
                            echo "<h5 class='mt-4 text-danger'><i class='bi bi-exclamation-triangle me-2'></i>Errors:</h5>";
                            echo "<ul class='list-group'>";
                            foreach ($errors as $error) {
                                echo "<li class='list-group-item list-group-item-danger'>{$error}</li>";
                            }
                            echo "</ul>";
                        }
                        ?>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="bi bi-key me-2"></i>Login Credentials</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm mb-0">
                                            <tr><td><strong>Admin:</strong></td><td><code>admin</code> / <code>admin123</code></td></tr>
                                            <tr><td><strong>Kasir:</strong></td><td><code>kasir</code> / <code>kasir123</code></td></tr>
                                            <tr><td><strong>Koki:</strong></td><td><code>koki</code> / <code>koki123</code></td></tr>
                                            <tr><td><strong>User:</strong></td><td><code>user</code> / <code>user123</code></td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bi bi-link me-2"></i>Quick Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="http://localhost:5173/login" class="btn btn-primary" target="_blank">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login Page
                                            </a>
                                            <a href="http://localhost:5173/register" class="btn btn-success" target="_blank">
                                                <i class="bi bi-person-plus me-2"></i>Go to Register Page
                                            </a>
                                            <button class="btn btn-warning" onclick="testLogin()">
                                                <i class="bi bi-bug me-2"></i>Test Login API
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Troubleshooting Tips</h6>
                                </div>
                                <div class="card-body">
                                    <ol>
                                        <li><strong>Clear Browser Cache:</strong> Press Ctrl+F5 to hard refresh</li>
                                        <li><strong>Check Network Tab:</strong> Open browser DevTools (F12) and check for API errors</li>
                                        <li><strong>Verify URL:</strong> Make sure you're accessing the correct login page</li>
                                        <li><strong>Try Different Browser:</strong> Test in incognito/private mode</li>
                                        <li><strong>Check Console:</strong> Look for JavaScript errors in browser console</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testLogin() {
            const credentials = {
                username: 'admin',
                password: 'admin123'
            };
            
            try {
                const response = await fetch('/api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(credentials)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Login API Test SUCCESSFUL!\n\nUser: ' + data.data.user.name + '\nRole: ' + data.data.user.role);
                } else {
                    alert('❌ Login API Test FAILED!\n\nError: ' + data.message);
                }
            } catch (error) {
                alert('❌ Login API Test ERROR!\n\nError: ' + error.message);
            }
        }
    </script>
</body>
</html>
