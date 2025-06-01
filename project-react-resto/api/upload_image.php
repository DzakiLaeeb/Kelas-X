<?php
header('Content-Type: application/json');
require_once 'config.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Response array
$response = ['success' => false, 'message' => '', 'filename' => ''];

try {
    // Check if file was uploaded
    if (!isset($_FILES['image'])) {
        throw new Exception('No image file uploaded');
    }

    $file = $_FILES['image'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Allowed extensions
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    
    // Validate file extension
    if (!in_array($fileExt, $allowed)) {
        throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowed));
    }
    
    // Validate file size (5MB max)
    if ($fileSize > 5000000) {
        throw new Exception('File is too large. Maximum size is 5MB');
    }
    
    // Check for upload errors
    if ($fileError !== 0) {
        throw new Exception('Error uploading file');
    }

    // Generate unique filename
    $newFileName = uniqid('menu_', true) . '.' . $fileExt;
    
    // Upload directory path
    $uploadDir = __DIR__ . '/../public/uploads/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Full path for the new file
    $uploadPath = $uploadDir . $newFileName;
    
    // Move uploaded file
    if (!move_uploaded_file($fileTmpName, $uploadPath)) {
        throw new Exception('Failed to move uploaded file');
    }
    
    // Success response
    $response['success'] = true;
    $response['message'] = 'File uploaded successfully';
    $response['filename'] = $newFileName;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);