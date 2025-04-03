<?php
// Test file upload functionality
$upload_dir = 'uploads/profiles/';

// Create directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Test file permissions
$test_file = $upload_dir . 'test.txt';
file_put_contents($test_file, 'test');
if (file_exists($test_file)) {
    echo "File upload test successful!";
    unlink($test_file);
} else {
    echo "File upload test failed!";
}
?>
