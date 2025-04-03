<?php
session_start();
require_once '../db_connection.php';

$conn = getConnection();
$message = '';

// Handle form submission for new banner
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $bannerType = $_POST['banner_type'] ?? 'slider';
        $table = $bannerType === 'promotion' ? 'banner_promosi' : 'banners';
        
        if ($_POST['action'] === 'add') {
            try {
                $title = $_POST['title'];
                $link = $_POST['link'];
                $price = isset($_POST['price']) ? $_POST['price'] : null;
                $image = $_FILES['image'];

                if ($image['error'] === 0) {
                    $target_dir = "../assets/" . ($bannerType === 'promotion' ? 'promotions/' : 'banners/');
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                    $newFilename = uniqid() . '.' . $imageFileType;
                    $target_file = $target_dir . $newFilename;

                    // Check if image file is actual image
                    $check = getimagesize($image["tmp_name"]);
                    if ($check === false) {
                        throw new Exception("File is not an image.");
                    }

                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        throw new Exception("Sorry, only JPG, JPEG, PNG files are allowed.");
                    }

                    if (move_uploaded_file($image["tmp_name"], $target_file)) {
                        if ($bannerType === 'promotion') {
                            $stmt = $conn->prepare("INSERT INTO banner_promosi (title, image_url, link, price) VALUES (?, ?, ?, ?)");
                            $image_url = "assets/promotions/" . $newFilename;
                            $stmt->execute([$title, $image_url, $link, $price]);
                        } else {
                            $stmt = $conn->prepare("INSERT INTO banners (title, image_url, link) VALUES (?, ?, ?)");
                            $image_url = "assets/banners/" . $newFilename;
                            $stmt->execute([$title, $image_url, $link]);
                        }
                        $message = ($bannerType === 'promotion' ? "Promotion" : "Banner") . " added successfully!";
                    }
                }
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'update' && isset($_POST['banner_id'])) {
            try {
                $banner_id = $_POST['banner_id'];
                $title = $_POST['title'];
                $link = $_POST['link'];
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    // Handle new image upload
                    $image = $_FILES['image'];
                    $target_dir = "../assets/banners/";
                    $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                    $newFilename = uniqid() . '.' . $imageFileType;
                    $target_file = $target_dir . $newFilename;

                    if (move_uploaded_file($image["tmp_name"], $target_file)) {
                        // Update with new image
                        $stmt = $conn->prepare("UPDATE banners SET title = ?, image_url = ?, link = ? WHERE id = ?");
                        $image_url = "assets/banners/" . $newFilename;
                        $stmt->execute([$title, $image_url, $link, $banner_id]);
                    }
                } else {
                    // Update without changing image
                    $stmt = $conn->prepare("UPDATE banners SET title = ?, link = ? WHERE id = ?");
                    $stmt->execute([$title, $link, $banner_id]);
                }
                $message = "Banner updated successfully!";
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}

// Delete banner
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    try {
        $banner_id = $_GET['delete'];
        
        // Get image URL before deleting
        $stmt = $conn->prepare("SELECT image_url FROM banners WHERE id = ?");
        $stmt->execute([$banner_id]);
        $banner = $stmt->fetch();
        
        if ($banner) {
            // Delete the physical file
            $file_path = "../" . $banner['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Delete from database
            $stmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
            $stmt->execute([$banner_id]);
            $message = "Banner deleted successfully!";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch all banners - Update the query with error handling
try {
    // First check if table exists
    $tableExists = $conn->query("SHOW TABLES LIKE 'banners'")->rowCount() > 0;
    
    if (!$tableExists) {
        // Drop the table if it exists with wrong structure
        $conn->exec("DROP TABLE IF EXISTS banners");
        
        // Create table with correct structure
        $createTableSQL = "CREATE TABLE banners (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            link VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->exec($createTableSQL);
        echo "<div class='message success'>Banners table created successfully</div>";
    }
    
    // Check if banner_promosi table exists
    $promoTableExists = $conn->query("SHOW TABLES LIKE 'banner_promosi'")->rowCount() > 0;
    
    if (!$promoTableExists) {
        // Create banner_promosi table
        $createPromoTableSQL = "CREATE TABLE banner_promosi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            link VARCHAR(255) NOT NULL,
            price DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->exec($createPromoTableSQL);
    }
    
    // Fetch banners based on type
    $currentType = isset($_GET['type']) ? $_GET['type'] : 'slider';
    if ($currentType === 'promotion') {
        $stmt = $conn->query("SELECT id, title, image_url, link, price, created_at FROM banner_promosi ORDER BY created_at DESC");
    } else {
        $stmt = $conn->query("SELECT id, title, image_url, link, created_at FROM banners ORDER BY created_at DESC");
    }
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    echo "<div class='message error'>Database Error: " . $e->getMessage() . "</div>";
    $banners = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-primary: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --font-heading: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            
            /* Font sizes */
            --text-xs: 0.75rem;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.25rem;
            --text-2xl: 1.5rem;
            --text-3xl: 1.875rem;
            
            /* Font weights */
            --font-normal: 400;
            --font-medium: 500;
            --font-semibold: 600;
            --font-bold: 700;
            --font-extrabold: 800;

            /* Line heights */
            --leading-none: 1;
            --leading-tight: 1.25;
            --leading-snug: 1.375;
            --leading-normal: 1.5;
            --leading-relaxed: 1.625;
            --leading-loose: 2;

            /* Letter spacing */
            --tracking-tighter: -0.05em;
            --tracking-tight: -0.025em;
            --tracking-normal: 0;
            --tracking-wide: 0.025em;
            --tracking-wider: 0.05em;
            --tracking-widest: 0.1em;

            --primary: #4a6cf7;
            --primary-dark: #3955d6;
            --secondary: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --background: #f8fafc;
            --surface: #ffffff;
            --text: #1e293b;
            --sidebar-hover-bg: #f8f9fa;
            --sidebar-hover-text: #495057;
            --sidebar-active-bg: rgba(74, 108, 247, 0.1);
            --sidebar-active-text: #0d6efd;
            --sidebar-text: #495057;
            --gray-border: #e9ecef;
        }

        * {
            font-family: var(--font-primary);
        }

        /* Update heading styles */
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            letter-spacing: var(--tracking-tight);
            line-height: var(--leading-tight);
        }

        /* Update existing styles with new typography */
        .banner-form h2 {
            font-size: var(--text-2xl);
            font-weight: var(--font-bold);
            letter-spacing: var(--tracking-tight);
        }

        .form-group label {
            font-size: var(--text-sm);
            font-weight: var(--font-medium);
            letter-spacing: var(--tracking-wide);
            text-transform: uppercase;
        }

        .form-group input {
            font-size: var(--text-base);
            font-weight: var(--font-normal);
            letter-spacing: var(--tracking-normal);
        }

        .banner-title {
            font-size: var(--text-lg);
            font-weight: var(--font-semibold);
            letter-spacing: var(--tracking-tight);
            line-height: var(--leading-snug);
        }

        .banner-link {
            font-size: var(--text-sm);
            font-weight: var(--font-medium);
            letter-spacing: var(--tracking-normal);
        }

        .btn {
            font-size: var(--text-sm);
            font-weight: var(--font-semibold);
            letter-spacing: var(--tracking-wide);
            text-transform: uppercase;
        }

        .message {
            font-size: var(--text-base);
            font-weight: var(--font-medium);
            letter-spacing: var(--tracking-normal);
        }

        .section-title {
            font-size: var(--text-2xl);
            font-weight: var(--font-extrabold);
            letter-spacing: var(--tracking-tight);
            margin-bottom: 2rem;
            color: var(--text);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-state i {
            font-size: var(--text-3xl);
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: var(--text-base);
            font-weight: var(--font-medium);
            color: var(--secondary);
            letter-spacing: var(--tracking-normal);
        }

        /* Modal Typography */
        #editModal h2 {
            font-size: var(--text-xl);
            font-weight: var(--font-bold);
            letter-spacing: var(--tracking-tight);
            margin-bottom: 1.5rem;
        }

        /* Responsive Typography */
        @media (max-width: 768px) {
            :root {
                --text-2xl: 1.25rem;
                --text-xl: 1.125rem;
                --text-lg: 1rem;
                --text-base: 0.9375rem;
                --text-sm: 0.875rem;
            }
        }

        .banner-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .banner-form {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 3rem;
            border: 1px solid rgba(74, 108, 247, 0.1);
        }

        .banner-form h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .banner-form h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            border-radius: 2px;
        }

        .banner-form h2 i {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(74, 108, 247, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        .file-upload-container {
            position: relative;
            width: 100%;
            min-height: 200px;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .file-upload-container:hover {
            border-color: var(--primary);
            background: rgba(74, 108, 247, 0.02);
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 2rem;
            text-align: center;
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .file-upload-text {
            font-size: 1rem;
            color: var(--secondary);
            margin: 0;
        }

        .file-upload-hint {
            font-size: 0.875rem;
            color: var(--secondary);
            opacity: 0.8;
        }

        .submit-button {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.2);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 108, 247, 0.3);
        }

        .submit-button i {
            font-size: 1.2em;
        }

        /* Preview Image Container */
        .image-preview {
            display: none;
            width: 100%;
            max-height: 200px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview.active {
            display: block;
        }

        /* Form Validation Styles */
        .form-group.error input {
            border-color: var(--danger);
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .form-group.error .error-message {
            display: block;
        }

        .banner-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .banner-item {
            background: var(--surface);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            position: relative;
        }

        .banner-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .banner-image-wrapper {
            position: relative;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            background: #f8fafc;
            overflow: hidden;
        }

        .banner-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .banner-item:hover .banner-image {
            transform: scale(1.05);
        }

        .banner-content {
            padding: 1.5rem;
        }

        .banner-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.75rem;
        }

        .banner-link {
            font-size: 0.9rem;
            color: var(--secondary);
            margin-bottom: 1rem;
            display: block;
            word-break: break-all;
        }

        .banner-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid var(--success);
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        #editModal {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        #editModal > div {
            background: var(--surface);
            padding: 2rem;
            border-radius: 16px;
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .banner-container {
                padding: 1rem;
            }
            
            .banner-form {
                padding: 1.5rem;
            }
            
            .banner-grid {
                grid-template-columns: 1fr;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
        }

        /* Modal Container */
        .modal-container {
            position: relative;
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-container {
            transform: translateY(0);
            opacity: 1;
        }

        /* Modal Header */
        .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        /* Modal Body */
        .modal-body {
            padding: 2rem;
        }

        .edit-form .form-group {
            margin-bottom: 1.5rem;
        }

        .edit-form label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
            outline: none;
            background: white;
        }

        /* File Input Styling */
        .file-input-wrapper {
            position: relative;
            width: 100%;
            height: 120px;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .file-input-wrapper:hover {
            border-color: var(--primary);
            background: rgba(74, 108, 247, 0.02);
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-input-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary);
        }

        .file-input-placeholder i {
            font-size: 2rem;
            color: var(--primary);
        }

        /* Modal Footer */
        .modal-footer {
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 16px 16px;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Button Styles */
        .modal-footer .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .modal-container {
                width: 95%;
                margin: 1rem auto;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                padding: 1rem;
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Sidebar and Header Styles */
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
            --header-height: 60px;
            --transition-speed: 0.3s;
            --primary-color: #5a8ef7;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1001;
            padding: 0 1rem;
            display: flex;
            align-items: center;
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .navbar-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 1rem;
            transition: background-color 0.2s;
        }
        
        /* Default sidebar state */
        .sidebar {
            width: 250px;
            transition: width 0.3s ease;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            padding-top: 60px; /* Adjust based on your header height */
            z-index: 100;
        }

        /* Main content default state */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* Collapsed sidebar state */
        .sidebar.collapsed {
            width: 60px; /* Width enough to show icons */
        }

        /* When sidebar is collapsed, hide text but keep icons */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* When sidebar is collapsed, center icons */
        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }

        /* Expanded main content when sidebar is collapsed */
        .main-content.expanded {
            margin-left: 60px;
        }

        /* Make sidebar toggle button more visible/clickable */
        .navbar-toggle {
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .sidebar.collapsed {
                width: 0;
            }
            
            .sidebar:not(.collapsed) {
                width: 250px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Default sidebar state */
        .sidebar {
            width: 250px;
            transition: width 0.3s ease;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            padding-top: 60px; /* Adjust based on your header height */
            z-index: 100;
        }

        /* Main content default state */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* Collapsed sidebar state */
        .sidebar.collapsed {
            width: 60px; /* Width enough to show icons */
        }

        /* When sidebar is collapsed, hide text but keep icons */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* When sidebar is collapsed, center icons */
        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }

        /* Expanded main content when sidebar is collapsed */
        .main-content.expanded {
            margin-left: 60px;
        }

        /* Make sidebar toggle button more visible/clickable */
        .navbar-toggle {
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.collapsed {
                width: 0;
            }
            
            .sidebar:not(.collapsed) {
                width: 250px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Add this to your CSS */
        .navbar-toggle {
            cursor: pointer;
            z-index: 1000; /* Make sure it's above other elements */
            padding: 10px;
            margin-right: 15px;
            position: relative; /* To ensure z-index works */
            display: block; /* Ensure it's visible */
            width: 40px; /* Give it a specific width */
            height: 40px; /* Give it a specific height */
            text-align: center;
            line-height: 40px;
            background: rgba(0,0,0,0.05); /* Slight background to see where it is */
            border-radius: 4px;
        }

        .navbar-toggle:hover {
            background: rgba(0,0,0,0.1);
        }

        /* Make icon clearly visible */
        .navbar-toggle i {
            font-size: 18px;
        }

        /* Fix any potential parent container issues */
        .header .d-flex {
            position: relative;
            z-index: 1000;
        }

        
        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            height: calc(100vh - var(--header-height));
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: var(--box-shadow);
            overflow-y: auto;
            transition: width var(--transition-speed) ease;
            z-index: 999;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-menu {
            padding: 0; /* Remove the top padding */
            margin-top: 0; /* Remove the top margin */
        }

        /* Add this class for proper spacing between sections */
        .sidebar-heading {
            padding: 12px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--secondary-color);
            letter-spacing: 0.5px;
            margin-top: 10px; /* Add space only above category headings */
        }

        /* Make sure the first sidebar item aligns properly */
        .sidebar-item:first-child {
            margin-top: 0;
            padding-top: 12px;
        }

        .sidebar-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color var(--transition-speed);
        }

        .sidebar-item:hover {
            background-color: #f8f9fa;
        }

        .sidebar-item.active {
            background-color: rgba(74, 108, 247, 0.1);
            border-left: 4px solid var(--primary-color);
            padding-left: 16px;
        }

        .sidebar-item.active .sidebar-icon,
        .sidebar-item.active .sidebar-text {
            color: var(--primary-color);
        }

        .sidebar-icon {
            font-size: 18px;
            width: 24px;
            color: var(--secondary-color);
            margin-right: 15px;
            text-align: center;
        }

        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar-heading {
            padding: 12px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--secondary-color);
            letter-spacing: 0.5px;
        }

        .sidebar.collapsed .sidebar-heading {
            display: none;
        }
        
        /* Update sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar-menu {
            padding: 1rem 0;
            margin-top: -5px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all var(--transition-speed);
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .sidebar-item:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--sidebar-hover-text);
            border-left-color: var(--gray-border);
        }

        .sidebar-item.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            border-left-color: var(--primary-color);
        }

        .sidebar-item.active .sidebar-icon,
        .sidebar-item.active .sidebar-text {
            color: var(--primary-color);
        }

        .sidebar-icon {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
            transition: all var(--transition-speed);
        }

        .sidebar-text {
            font-size: 0.95rem;
            font-weight: 500;
            white-space: nowrap;
            opacity: 1;
            transition: all var(--transition-speed);
        }

        /* Collapsed state */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-heading {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }

        /* Main content adjustments */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed) ease;
            min-height: 100vh;
            padding: 1rem;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.collapsed {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* Add this to your existing CSS */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1001;
            padding: 0 1rem;
            display: flex;
            align-items: center;
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .navbar-toggle {
            background: transparent;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--sidebar-text);
            cursor: pointer;
            border-radius: 4px;
            margin-right: 1rem;
            transition: background-color 0.2s;
        }

        .navbar-toggle:hover {
            background-color: var(--sidebar-hover);
        }

        /* Adjust main content padding */
        .main-content {
            padding-top: calc(var(--header-height) + 1rem);
        }

        .banner-type-toggle {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            padding: 0.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .toggle-btn {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: var(--secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .toggle-btn i {
            font-size: 1.1em;
        }

        .toggle-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.2);
        }

        .toggle-btn:hover:not(.active) {
            background: rgba(74, 108, 247, 0.1);
            color: var(--primary);
        }
    </style>
</head>

<body>
    <header class="header">
        <button id="navbarToggle" class="navbar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Toko Online</h2>
    </header>

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-menu">
            <a href="index.php" class="sidebar-item">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <div class="sidebar-heading">Management</div>
            <a href="users.php" class="sidebar-item">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Users</span>
            </a>
            <a href="edit_product.php" class="sidebar-item">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">Products</span>
            </a>
            <a href="orders.php" class="sidebar-item">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Orders</span>
            </a>
            <a href="banners.php" class="sidebar-item active">
                <i class="fas fa-images sidebar-icon"></i>
                <span class="sidebar-text">Banners</span>
            </a>
            <div class="sidebar-heading">Reports</div>
            <a href="analytics.php" class="sidebar-item">
                <i class="fas fa-chart-bar sidebar-icon"></i>
                <span class="sidebar-text">Analytics</span>
            </a>
            <a href="sales.php" class="sidebar-item">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Sales</span>
            </a>
            <div class="sidebar-heading">Settings</div>
            <a href="general.php" class="sidebar-item">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">General</span>
            </a>
            <a href="account.php" class="sidebar-item">
                <i class="fas fa-user-cog sidebar-icon"></i>
                <span class="sidebar-text">Account</span>
            </a>
            <a href="logout.php" class="sidebar-item">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </aside>

    <div id="mainContent" class="main-content">
        <div class="banner-container">
            <!-- Add toggle button -->
            <div class="banner-type-toggle">
                <button class="toggle-btn active" data-type="slider">
                    <i class="fas fa-sliders-h"></i> Banner Slider
                </button>
                <button class="toggle-btn" data-type="promotion">
                    <i class="fas fa-bullhorn"></i> Banner Promotion
                </button>
            </div>

            <div class="banner-form">
                <h2 id="formTitle">
                    <i class="fas fa-plus-circle"></i> 
                    <span id="formTitleText">Add New Banner Slider</span>
                </h2>
                <form action="" method="POST" enctype="multipart/form-data" id="addBannerForm">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="banner_type" id="bannerType" value="slider">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">
                                <i class="fas fa-heading"></i> Title
                            </label>
                            <input type="text" id="title" name="title" required 
                                placeholder="Enter an attractive title">
                            <div class="error-message">Please enter a title</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="link">
                                <i class="fas fa-link"></i> Link URL
                            </label>
                            <input type="text" id="link" name="link" required 
                                placeholder="Enter the destination URL">
                            <div class="error-message">Please enter a valid URL</div>
                        </div>
                    </div>

                    <div class="form-group price-field" style="display: none;">
                        <label for="price">
                            <i class="fas fa-tag"></i> Price
                        </label>
                        <input type="number" id="price" name="price" placeholder="Enter price (optional)"
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="image" id="imageLabel">
                            <i class="fas fa-image"></i> 
                            <span id="uploadText">Upload Banner Slider</span>
                        </label>
                        <div class="file-upload-container" id="dropZone">
                            <input type="file" id="image" name="image" 
                                accept="image/*" required class="file-upload-input">
                            <div class="file-upload-content">
                                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                <p class="file-upload-text">Drag and drop your image here or click to browse</p>
                                <span class="file-upload-hint">Supports: JPG, PNG, JPEG (Max 5MB)</span>
                            </div>
                        </div>
                        <div class="image-preview" id="imagePreview">
                            <img src="" alt="Preview">
                        </div>
                    </div>

                    <button type="submit" class="submit-button">
                        <i class="fas fa-plus"></i>
                        <span id="submitButtonText">Add Banner</span>
                    </button>
                </form>
            </div>

            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                    <i class="fas fa-<?php echo strpos($message, 'Error') !== false ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <h2 class="section-title"><i class="fas fa-images"></i> Existing Banners</h2>
            <div class="banner-grid">
                <?php if (!empty($banners)): ?>
                    <?php foreach ($banners as $banner): ?>
                        <div class="banner-item">
                            <div class="banner-image-wrapper">
                                <img 
                                    src="../<?php echo htmlspecialchars($banner['image_url'] ?? ''); ?>" 
                                    alt="<?php echo htmlspecialchars($banner['title'] ?? 'Banner Image'); ?>" 
                                    class="banner-image"
                                >
                            </div>
                            <div class="banner-content">
                                <h3 class="banner-title"><?php echo htmlspecialchars($banner['title'] ?? 'Untitled'); ?></h3>
                                <a href="<?php echo htmlspecialchars($banner['link'] ?? '#'); ?>" 
                                class="banner-link" 
                                target="_blank"
                                ><?php echo htmlspecialchars($banner['link'] ?? 'No link provided'); ?></a>
                                <div class="banner-actions">
                                    <button class="btn btn-primary" onclick='showEditForm(<?php echo json_encode($banner); ?>)'>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger" onclick="deleteBanner(<?php echo htmlspecialchars($banner['id'] ?? 0); ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-image"></i>
                        <p>No banners found. Add your first banner using the form above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Banner Modal (hidden by default) -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Banner</h2>
                <button type="button" class="modal-close" onclick="hideEditForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" class="edit-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="banner_id" id="edit_banner_id">
                    
                    <div class="form-group">
                        <label for="edit_title">
                            <i class="fas fa-heading"></i>
                            Title
                        </label>
                        <input type="text" id="edit_title" name="title" required 
                            class="form-input" placeholder="Enter banner title">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_link">
                            <i class="fas fa-link"></i>
                            Link
                        </label>
                        <input type="text" id="edit_link" name="link" required 
                            class="form-input" placeholder="Enter banner link">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_image">
                            <i class="fas fa-image"></i>
                            New Image (optional)
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" id="edit_image" name="image" 
                                accept="image/*" class="file-input">
                            <div class="file-input-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose a file or drag it here</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="hideEditForm()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && mainContent) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save sidebar state
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarCollapsed');
            
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });

        function showEditForm(banner) {
            const modal = document.getElementById('editModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Set form values
            document.getElementById('edit_banner_id').value = banner.id;
            document.getElementById('edit_title').value = banner.title;
            document.getElementById('edit_link').value = banner.link;
            
            // Add active class after a small delay for animation
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }

        function hideEditForm() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300); // Match this with CSS transition duration
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideEditForm();
            }
        });

        // File input preview and drag-drop functionality
        document.getElementById('edit_image').addEventListener('change', function(e) {
            const placeholder = e.target.parentElement.querySelector('.file-input-placeholder');
            if (this.files && this.files[0]) {
                placeholder.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <span>${this.files[0].name}</span>
                `;
            }
        });

        // Add this JavaScript for the enhanced form functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = imagePreview.querySelector('img');

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('hover');
            }

            function unhighlight(e) {
                dropZone.classList.remove('hover');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                handleFiles(files);
            }

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            imagePreview.classList.add('active');
                            dropZone.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }

            // Fixed toggle buttons functionality
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            const formTitleText = document.getElementById('formTitleText');
            const uploadText = document.getElementById('uploadText');
            const submitButtonText = document.getElementById('submitButtonText');
            const priceField = document.querySelector('.price-field');
            const bannerTypeInput = document.getElementById('bannerType');
            
            // Get current type from URL or use default
            const urlParams = new URLSearchParams(window.location.search);
            const currentType = urlParams.get('type') || 'slider';
            
            // Initialize the form based on current type
            updateFormForType(currentType);
            
            // Set active button based on current type
            toggleBtns.forEach(btn => {
                if (btn.dataset.type === currentType) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Add event listeners for toggle buttons
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default action
                    
                    const type = this.dataset.type;
                    
                    // Only proceed if this is a different type than current
                    if (type !== currentType) {
                        // Update UI immediately to avoid the jumpy effect
                        toggleBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Update form without page reload
                        updateFormForType(type);
                        
                        // Update URL with History API instead of redirecting
                        const newUrl = updateQueryStringParameter(window.location.href, 'type', type);
                        history.pushState({ type: type }, '', newUrl);
                        
                        // Fetch banners of the selected type via AJAX
                        fetchBannersByType(type);
                    }
                });
            });
            
            // Handle back/forward browser navigation
            window.addEventListener('popstate', function(event) {
                if (event.state && event.state.type) {
                    const type = event.state.type;
                    updateFormForType(type);
                    toggleBtns.forEach(btn => {
                        btn.classList.toggle('active', btn.dataset.type === type);
                    });
                    fetchBannersByType(type);
                }
            });
            
            // Function to update the form based on banner type
            function updateFormForType(type) {
                bannerTypeInput.value = type;
                
                if (type === 'promotion') {
                    formTitleText.textContent = 'Add New Banner Promotion';
                    uploadText.textContent = 'Upload Banner Promosi';
                    submitButtonText.textContent = 'Add Promotion';
                    priceField.style.display = 'block';
                } else {
                    formTitleText.textContent = 'Add New Banner Slider';
                    uploadText.textContent = 'Upload Banner Slider';
                    submitButtonText.textContent = 'Add Banner';
                    priceField.style.display = 'none';
                }
            }
            
            // Function to update query string parameter
            function updateQueryStringParameter(uri, key, value) {
                const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                const separator = uri.indexOf('?') !== -1 ? "&" : "?";
                
                if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                } else {
                    return uri + separator + key + "=" + value;
                }
            }
            
            // Function to fetch banners of a specific type via AJAX
            function fetchBannersByType(type) {
                // Create a spinner or loading indicator
                const bannerGrid = document.querySelector('.banner-grid');
                if (bannerGrid) {
                    bannerGrid.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
                }
                
                // Use fetch API to get banners
                fetch(`banners_ajax.php?type=${type}`)
                    .then(response => response.text())
                    .then(html => {
                        // Update the banner grid with new content
                        if (bannerGrid) {
                            bannerGrid.innerHTML = html;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching banners:', error);
                        if (bannerGrid) {
                            bannerGrid.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Failed to load banners</div>';
                        }
                    });
            }
        });

        // Function to clear image preview and show drop zone again
        function clearImagePreview() {
            const imagePreview = document.getElementById('imagePreview');
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('image');
            
            imagePreview.classList.remove('active');
            imagePreview.querySelector('img').src = '';
            dropZone.style.display = 'block';
            fileInput.value = ''; // Clear the file input
        }

        // Function to delete a banner (confirmation and AJAX)
        function deleteBanner(bannerId) {
            if (confirm('Are you sure you want to delete this banner?')) {
                // Create a form data object
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('banner_id', bannerId);
                
                // Use fetch to send the delete request
                fetch('banners.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the banner element from DOM
                        const bannerElement = document.querySelector(`.banner-item[data-id="${bannerId}"]`);
                        if (bannerElement) {
                            bannerElement.remove();
                        }
                        
                        // Show success message
                        showMessage('Banner deleted successfully', 'success');
                    } else {
                        // Show error message
                        showMessage(data.message || 'Error deleting banner', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error deleting banner', 'error');
                });
            }
        }

        // Function to show temporary messages
        function showMessage(message, type = 'success') {
            // Create message element
            const messageEl = document.createElement('div');
            messageEl.className = `message ${type}`;
            messageEl.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                ${message}
            `;
            
            // Append to container
            const container = document.querySelector('.banner-container');
            container.insertBefore(messageEl, container.firstChild);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                messageEl.classList.add('fade-out');
                setTimeout(() => {
                    messageEl.remove();
                }, 500);
            }, 5000);
        }
        document.addEventListener('DOMContentLoaded', function() {
        // Referensi ke elemen-elemen yang diperlukan
        const toggleBtns = document.querySelectorAll('.toggle-btn');
        const formTitleText = document.getElementById('formTitleText');
        const uploadText = document.getElementById('uploadText');
        const submitButtonText = document.getElementById('submitButtonText');
        const priceField = document.querySelector('.price-field');
        const bannerTypeInput = document.getElementById('bannerType');
        
        // Menangani klik pada tombol toggle
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Menghapus class active dari semua tombol
                toggleBtns.forEach(b => b.classList.remove('active'));
                
                // Menambahkan class active ke tombol yang diklik
                this.classList.add('active');
                
                // Mengambil tipe banner dari atribut data-type
                const type = this.dataset.type;
                
                // Mengupdate nilai input hidden
                bannerTypeInput.value = type;
                
                // Mengupdate teks di form berdasarkan tipe yang dipilih
                if (type === 'promotion') {
                    formTitleText.textContent = 'Add New Banner Promotion';
                    uploadText.textContent = 'Upload Banner Promosi';
                    submitButtonText.textContent = 'Add Promotion';
                    priceField.style.display = 'block';
                } else {
                    formTitleText.textContent = 'Add New Banner Slider';
                    uploadText.textContent = 'Upload Banner Slider';
                    submitButtonText.textContent = 'Add Banner';
                    priceField.style.display = 'none';
                }
                
                // Simpan tipe yang aktif di localStorage
                localStorage.setItem('activeBannerType', type);
                
                // Buat request untuk mengambil banner yang sesuai
                // Gunakan setTimeout untuk menghindari bug toggle
                setTimeout(function() {
                    window.location.href = `banners.php?type=${type}`;
                }, 50);
            });
        });
        
        // Set tipe awal berdasarkan parameter URL
        const urlParams = new URLSearchParams(window.location.search);
        const typeFromUrl = urlParams.get('type');
        
        // Jika ada parameter type di URL, gunakan itu, jika tidak, cek localStorage
        let currentType;
        if (typeFromUrl) {
            currentType = typeFromUrl;
        } else {
            // Ambil dari localStorage jika ada, default ke 'slider' jika tidak ada
            currentType = localStorage.getItem('activeBannerType') || 'slider';
        }
        
        // Atur UI berdasarkan tipe saat ini
        if (currentType === 'promotion') {
            document.querySelector('[data-type="promotion"]').classList.add('active');
            document.querySelector('[data-type="slider"]').classList.remove('active');
            formTitleText.textContent = 'Add New Banner Promotion';
            uploadText.textContent = 'Upload Banner Promosi';
            submitButtonText.textContent = 'Add Promotion';
            priceField.style.display = 'block';
            bannerTypeInput.value = 'promotion';
        } else {
            document.querySelector('[data-type="slider"]').classList.add('active');
            document.querySelector('[data-type="promotion"]').classList.remove('active');
            formTitleText.textContent = 'Add New Banner Slider';
            uploadText.textContent = 'Upload Banner Slider';
            submitButtonText.textContent = 'Add Banner';
            priceField.style.display = 'none';
            bannerTypeInput.value = 'slider';
        }
        
        // Solusi tambahan untuk memastikan tombol toggle bekerja dengan baik
        const sliderBtn = document.querySelector('[data-type="slider"]');
        const promotionBtn = document.querySelector('[data-type="promotion"]');
        
        sliderBtn.onclick = function() {
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const type = this.dataset.type;
            bannerTypeInput.value = type;
            formTitleText.textContent = 'Add New Banner Slider';
            uploadText.textContent = 'Upload Banner Slider';
            submitButtonText.textContent = 'Add Banner';
            priceField.style.display = 'none';
            localStorage.setItem('activeBannerType', type);
            setTimeout(function() {
                window.location.href = `banners.php?type=${type}`;
            }, 50);
        };
        
        promotionBtn.onclick = function() {
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const type = this.dataset.type;
            bannerTypeInput.value = type;
            formTitleText.textContent = 'Add New Banner Promotion';
            uploadText.textContent = 'Upload Banner Promosi';
            submitButtonText.textContent = 'Add Promotion';
            priceField.style.display = 'block';
            localStorage.setItem('activeBannerType', type);
            setTimeout(function() {
                window.location.href = `banners.php?type=${type}`;
            }, 50);
        };
    });
    </script>
</body>
</html>
