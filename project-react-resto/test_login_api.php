<?php
/**
 * Test Login API
 * Script untuk test login API dengan berbagai role
 */

echo "<h2>🔐 Test Login API</h2>";
echo "<hr>";

// Test data untuk berbagai role
$testUsers = [
    ['username' => 'admin@resto.com', 'password' => 'admin123', 'role' => 'admin'],
    ['username' => 'koki@resto.com', 'password' => 'koki123', 'role' => 'koki'],
    ['username' => 'kasir@resto.com', 'password' => 'kasir123', 'role' => 'kasir'],
    ['username' => 'user@resto.com', 'password' => 'user123', 'role' => 'user']
];

foreach ($testUsers as $testUser) {
    echo "<h4>Testing {$testUser['role']}: {$testUser['username']}</h4>";
    
    // Prepare POST data
    $postData = json_encode([
        'username' => $testUser['username'],
        'password' => $testUser['password']
    ]);
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/project-react-resto/api/login.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Parse response
    $responseData = json_decode($response, true);
    
    echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>HTTP Code:</strong> $httpCode<br>";
    
    if ($responseData) {
        echo "<strong>Success:</strong> " . ($responseData['success'] ? 'Yes' : 'No') . "<br>";
        echo "<strong>Message:</strong> " . ($responseData['message'] ?? 'No message') . "<br>";
        
        if (isset($responseData['data']['user'])) {
            $user = $responseData['data']['user'];
            echo "<strong>User:</strong> {$user['name']} ({$user['role']})<br>";
            echo "<strong>Email:</strong> {$user['email']}<br>";
        }
        
        if ($responseData['success']) {
            echo "<span style='color: green;'>✅ Login berhasil!</span>";
        } else {
            echo "<span style='color: red;'>❌ Login gagal!</span>";
        }
    } else {
        echo "<span style='color: red;'>❌ Invalid JSON response</span><br>";
        echo "<strong>Raw Response:</strong> " . htmlspecialchars($response);
    }
    echo "</div>";
    echo "<hr>";
}

// Test form manual
echo "<h3>🧪 Manual Test Form</h3>";
echo "<form method='post' style='background: #f8f9fa; padding: 20px; border-radius: 5px;'>";
echo "<div style='margin-bottom: 10px;'>";
echo "<label>Username/Email:</label><br>";
echo "<input type='text' name='test_username' value='koki@resto.com' style='width: 300px; padding: 5px;'>";
echo "</div>";
echo "<div style='margin-bottom: 10px;'>";
echo "<label>Password:</label><br>";
echo "<input type='password' name='test_password' value='koki123' style='width: 300px; padding: 5px;'>";
echo "</div>";
echo "<button type='submit' name='manual_test' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;'>Test Login</button>";
echo "</form>";

// Handle manual test
if (isset($_POST['manual_test'])) {
    echo "<h4>Manual Test Result:</h4>";
    
    $postData = json_encode([
        'username' => $_POST['test_username'],
        'password' => $_POST['test_password']
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/project-react-resto/api/login.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Request:</strong><br>";
    echo "<pre>" . htmlspecialchars($postData) . "</pre>";
    echo "<strong>Response (HTTP $httpCode):</strong><br>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    echo "</div>";
}

echo "<br><h4>🔗 Quick Links:</h4>";
echo "<ul>";
echo "<li><a href='http://localhost:5176/login' target='_blank'>🔐 Frontend Login Page</a></li>";
echo "<li><a href='http://localhost/phpmyadmin/index.php?route=/database/structure&db=apirestoran' target='_blank'>🗄️ Database Users Table</a></li>";
echo "<li><a href='create_role_users.php' target='_blank'>👥 Create Role Users</a></li>";
echo "</ul>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}

h2, h3, h4 {
    color: #333;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 3px;
    overflow-x: auto;
}

hr {
    border: none;
    height: 1px;
    background: #dee2e6;
    margin: 20px 0;
}
</style>
