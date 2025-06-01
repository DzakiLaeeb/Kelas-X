<?php
// Comprehensive Authentication Diagnosis
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnose Authentication Issues</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .test-result { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-0"><i class="bi bi-bug-fill me-2"></i>Authentication Diagnosis</h3>
            </div>
            <div class="card-body">
                
                <?php
                echo "<h4>🔍 COMPREHENSIVE DIAGNOSIS</h4>";
                echo "<hr>";
                
                $issues = [];
                $fixes = [];
                
                // Test 1: PHP Configuration
                echo "<h5>1️⃣ PHP Configuration</h5>";
                $phpVersion = phpversion();
                echo "<div class='test-result info'>";
                echo "✅ PHP Version: $phpVersion<br>";
                echo "✅ JSON Extension: " . (extension_loaded('json') ? 'Loaded' : 'NOT LOADED') . "<br>";
                echo "✅ MySQLi Extension: " . (extension_loaded('mysqli') ? 'Loaded' : 'NOT LOADED') . "<br>";
                echo "✅ CURL Extension: " . (extension_loaded('curl') ? 'Loaded' : 'NOT LOADED') . "<br>";
                echo "</div>";
                
                // Test 2: Database Connection
                echo "<h5>2️⃣ Database Connection</h5>";
                try {
                    $host = 'localhost';
                    $user = 'root';
                    $pass = '';
                    $db = 'apirestoran';
                    
                    $conn = new mysqli($host, $user, $pass, $db);
                    
                    if ($conn->connect_error) {
                        throw new Exception('Connection failed: ' . $conn->connect_error);
                    }
                    
                    echo "<div class='test-result success'>✅ Database connection successful</div>";
                    
                    // Test 3: Check Users Table
                    echo "<h5>3️⃣ Users Table Structure</h5>";
                    $result = $conn->query("SHOW TABLES LIKE 'users'");
                    
                    if ($result->num_rows == 0) {
                        echo "<div class='test-result error'>❌ Users table does not exist!</div>";
                        $issues[] = "Users table missing";
                        $fixes[] = "Create users table";
                        
                        // Create users table
                        $createSQL = "
                        CREATE TABLE users (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            username VARCHAR(255) UNIQUE NOT NULL,
                            email VARCHAR(255) UNIQUE NOT NULL,
                            password VARCHAR(255) NOT NULL,
                            role ENUM('admin', 'user', 'koki', 'kasir') DEFAULT 'user',
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                        )";
                        
                        if ($conn->query($createSQL)) {
                            echo "<div class='test-result success'>✅ Users table created successfully</div>";
                        } else {
                            echo "<div class='test-result error'>❌ Failed to create users table: " . $conn->error . "</div>";
                        }
                    } else {
                        echo "<div class='test-result success'>✅ Users table exists</div>";
                        
                        // Show table structure
                        $result = $conn->query("DESCRIBE users");
                        echo "<div class='test-result info'>";
                        echo "<strong>Table Structure:</strong><br>";
                        while ($row = $result->fetch_assoc()) {
                            echo "- {$row['Field']} ({$row['Type']})<br>";
                        }
                        echo "</div>";
                    }
                    
                    // Test 4: Check Users Data
                    echo "<h5>4️⃣ Users Data</h5>";
                    $result = $conn->query("SELECT COUNT(*) as count FROM users");
                    $count = $result->fetch_assoc()['count'];
                    
                    if ($count == 0) {
                        echo "<div class='test-result warning'>⚠️ No users in database. Creating default users...</div>";
                        
                        // Create default users
                        $users = [
                            ['admin', 'Administrator', 'admin@test.com', 'admin123', 'admin'],
                            ['kasir', 'Kasir Demo', 'kasir@test.com', 'kasir123', 'kasir'],
                            ['koki', 'Koki Demo', 'koki@test.com', 'koki123', 'koki'],
                            ['user', 'User Demo', 'user@test.com', 'user123', 'user']
                        ];
                        
                        foreach ($users as $userData) {
                            $username = $userData[0];
                            $name = $userData[1];
                            $email = $userData[2];
                            $password = password_hash($userData[3], PASSWORD_DEFAULT);
                            $role = $userData[4];
                            
                            $stmt = $conn->prepare("INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssss", $username, $name, $email, $password, $role);
                            
                            if ($stmt->execute()) {
                                echo "<div class='test-result success'>✅ Created user: $username</div>";
                            } else {
                                echo "<div class='test-result error'>❌ Failed to create user $username: " . $stmt->error . "</div>";
                            }
                        }
                    } else {
                        echo "<div class='test-result success'>✅ Found $count users in database</div>";
                        
                        // Show users
                        $result = $conn->query("SELECT username, name, email, role FROM users");
                        echo "<div class='test-result info'>";
                        echo "<strong>Existing Users:</strong><br>";
                        while ($row = $result->fetch_assoc()) {
                            echo "- {$row['username']} ({$row['name']}) - {$row['role']}<br>";
                        }
                        echo "</div>";
                    }
                    
                } catch (Exception $e) {
                    echo "<div class='test-result error'>❌ Database error: " . $e->getMessage() . "</div>";
                    $issues[] = "Database connection failed";
                    $fixes[] = "Check XAMPP MySQL service";
                }
                
                // Test 5: API Files
                echo "<h5>5️⃣ API Files Check</h5>";
                $apiFiles = ['api/login.php', 'api/register.php', 'api/config.php'];
                
                foreach ($apiFiles as $file) {
                    if (file_exists($file)) {
                        echo "<div class='test-result success'>✅ $file exists</div>";
                    } else {
                        echo "<div class='test-result error'>❌ $file missing</div>";
                        $issues[] = "$file missing";
                    }
                }
                
                // Test 6: Test Login API
                echo "<h5>6️⃣ Login API Test</h5>";
                echo "<div id='api-test-results'>";
                echo "<button class='btn btn-primary' onclick='testLoginAPI()'>Test Login API</button>";
                echo "<div id='login-results' class='mt-3'></div>";
                echo "</div>";
                
                // Test 7: Test Register API
                echo "<h5>7️⃣ Register API Test</h5>";
                echo "<div id='register-test-results'>";
                echo "<button class='btn btn-success' onclick='testRegisterAPI()'>Test Register API</button>";
                echo "<div id='register-results' class='mt-3'></div>";
                echo "</div>";
                
                // Summary
                echo "<hr>";
                echo "<h4>📋 SUMMARY</h4>";
                
                if (empty($issues)) {
                    echo "<div class='test-result success'>";
                    echo "<h5>🎉 All tests passed!</h5>";
                    echo "Your authentication system should be working now.";
                    echo "</div>";
                } else {
                    echo "<div class='test-result error'>";
                    echo "<h5>❌ Issues found:</h5>";
                    foreach ($issues as $issue) {
                        echo "- $issue<br>";
                    }
                    echo "</div>";
                    
                    echo "<div class='test-result warning'>";
                    echo "<h5>🔧 Recommended fixes:</h5>";
                    foreach ($fixes as $fix) {
                        echo "- $fix<br>";
                    }
                    echo "</div>";
                }
                ?>
                
                <hr>
                <h4>🚀 Quick Actions</h4>
                <div class="row">
                    <div class="col-md-3">
                        <a href="http://localhost:5173/login" class="btn btn-primary w-100" target="_blank">
                            <i class="bi bi-box-arrow-in-right me-2"></i>React Login
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="http://localhost:5173/register" class="btn btn-success w-100" target="_blank">
                            <i class="bi bi-person-plus me-2"></i>React Register
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Re-run Tests
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="test_login_simple.html" class="btn btn-info w-100" target="_blank">
                            <i class="bi bi-bug me-2"></i>Simple Test
                        </a>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-primary text-white rounded">
                    <h6><i class="bi bi-key me-2"></i>Test Credentials</h6>
                    <div class="row">
                        <div class="col-6">
                            <small>
                                <strong>Admin:</strong> admin / admin123<br>
                                <strong>Kasir:</strong> kasir / kasir123
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <strong>Koki:</strong> koki / koki123<br>
                                <strong>User:</strong> user / user123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testLoginAPI() {
            const resultsDiv = document.getElementById('login-results');
            resultsDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Testing...';
            
            try {
                const response = await fetch('/api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: 'admin', password: 'admin123' })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    resultsDiv.innerHTML = `
                        <div class="test-result success">
                            ✅ Login API Working!<br>
                            User: ${data.data.user.name}<br>
                            Role: ${data.data.user.role}
                        </div>`;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="test-result error">
                            ❌ Login Failed: ${data.message}
                        </div>`;
                }
            } catch (error) {
                resultsDiv.innerHTML = `
                    <div class="test-result error">
                        ❌ API Error: ${error.message}
                    </div>`;
            }
        }
        
        async function testRegisterAPI() {
            const resultsDiv = document.getElementById('register-results');
            resultsDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Testing...';
            
            const testUser = {
                name: 'Test User',
                username: 'testuser' + Date.now(),
                email: 'test' + Date.now() + '@example.com',
                password: 'test123'
            };
            
            try {
                const response = await fetch('/api/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(testUser)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    resultsDiv.innerHTML = `
                        <div class="test-result success">
                            ✅ Register API Working!<br>
                            Created user: ${data.data.user.name}
                        </div>`;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="test-result error">
                            ❌ Register Failed: ${data.message}
                        </div>`;
                }
            } catch (error) {
                resultsDiv.innerHTML = `
                    <div class="test-result error">
                        ❌ API Error: ${error.message}
                    </div>`;
            }
        }
    </script>
</body>
</html>
