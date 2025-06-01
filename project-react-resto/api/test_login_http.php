<?php
// Test login API via HTTP request simulation
echo "Testing Login API via HTTP Request...\n\n";

// Simulate POST request data
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];

// Simulate JSON input
$jsonInput = json_encode([
    'username' => 'admin',
    'password' => 'admin123'
]);

// Mock php://input
file_put_contents('php://temp', $jsonInput);

// Capture output
ob_start();

// Include the login API
include 'login.php';

// Get the output
$output = ob_get_clean();

echo "API Response:\n";
echo $output . "\n";

// Parse and validate response
$response = json_decode($output, true);

if ($response) {
    echo "\nParsed Response:\n";
    echo "Success: " . ($response['success'] ? 'true' : 'false') . "\n";
    echo "Message: " . ($response['message'] ?? 'N/A') . "\n";
    
    if (isset($response['data'])) {
        echo "User ID: " . ($response['data']['user']['id'] ?? 'N/A') . "\n";
        echo "Username: " . ($response['data']['user']['username'] ?? 'N/A') . "\n";
        echo "Role: " . ($response['data']['user']['role'] ?? 'N/A') . "\n";
        echo "Token: " . (isset($response['data']['token']) ? substr($response['data']['token'], 0, 20) . '...' : 'N/A') . "\n";
    }
} else {
    echo "\n❌ Failed to parse JSON response\n";
}
?>
