<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'tokoonline';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $_SESSION['error_message'] = "Connection failed: " . $e->getMessage();
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];

    // Input validation
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    $address = trim($_POST['address']);

    // Validasi input seperti sebelumnya...

    // Handle file upload
    $profile_image = null;
    // Perbaikan: Gunakan path relatif untuk upload dan akses web
    $upload_dir = 'uploads/profiles/'; // Path relatif untuk penyimpanan
    $upload_path_abs = $_SERVER['DOCUMENT_ROOT'] . '/' . $upload_dir; // Path absolut untuk operasi file
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validasi file type dan size seperti sebelumnya...

        // Generate unique filename
        $new_filename = bin2hex(random_bytes(8)) . '_' . time() . '.' . $file_ext;
        $upload_path = $upload_path_abs . $new_filename;

        // Create directory if it doesn't exist
        if (!file_exists($upload_path_abs)) {
            if (!mkdir($upload_path_abs, 0755, true)) {
                error_log("Failed to create upload directory: $upload_path_abs");
                $_SESSION['error_message'] = "Failed to create upload directory.";
                header("Location: profile.php");
                exit();
            }
        }

        // Upload file
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
            chmod($upload_path, 0644);
            
            // Delete old profile image
            $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $old_image = $stmt->fetchColumn();
            
            if ($old_image && $old_image != 'default-avatar.jpg' && file_exists($upload_path_abs . $old_image)) {
                unlink($upload_path_abs . $old_image);
            }
            
            $profile_image = $new_filename;
            
            // Debug log
            error_log("Upload successful. File path: $upload_path");
            error_log("Web accessible path: " . $upload_dir . $new_filename);
        } else {
            error_log("Upload failed. Error code: " . $_FILES['profile_image']['error']);
            $_SESSION['error_message'] = "Failed to upload image.";
            header("Location: profile.php");
            exit();
        }
    }

    try {
        $pdo->beginTransaction();

        // Update query
        if ($profile_image) {
            $sql = "UPDATE users SET 
                    name = :name,
                    email = :email,
                    phone = :phone,
                    address = :address,
                    profile_image = :profile_image,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':profile_image' => $profile_image,
                ':id' => $userId
            ];
        } else {
            // Query tanpa update profile_image
            $sql = "UPDATE users SET 
                    name = :name,
                    email = :email,
                    phone = :phone,
                    address = :address,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':id' => $userId
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $pdo->commit();
        $_SESSION['success_message'] = "Profile updated successfully!";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        
        if ($profile_image && file_exists($upload_path)) {
            unlink($upload_path);
        }

        error_log("Profile update error: " . $e->getMessage());
        $_SESSION['error_message'] = "Error updating profile.";
    }

    header("Location: profile.php");
    exit();
}
?>