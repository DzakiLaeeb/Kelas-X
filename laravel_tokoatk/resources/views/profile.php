<?php
session_start();

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
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$profile_image = $stmt->fetchColumn();

// Get user data
    $userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();


// Tentukan direktori untuk menyimpan gambar
$upload_dir = 'uploads/profiles/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f0f8ff;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 5px solid #1e90ff;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .profile-image:hover {
            transform: scale(1.05);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .profile-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #2c3e50;
            font-size: 16px;
        }

        .edit-btn {
            background: #1e90ff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .edit-btn:hover {
            background: #187bcd;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal.active {
            display: flex;
        }

        .modal.active .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
                
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            animation: slideDown 0.5s ease-out;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
       
        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #1e90ff;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .image-overlay:hover {
            opacity: 1;
        }

        .image-overlay span {
            color: white;
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }

        #imageUpload {
            display: none;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-image-container">
                    <div class="profile-image" onclick="document.getElementById('imageUpload').click();">
                    <?php
                    $profileImagePath = $upload_dir . ($user['profile_image'] ?? 'default-avatar.jpg');
                    if (!file_exists($profileImagePath)) {
                        $profileImagePath = $upload_dir . 'default-avatar.jpg';
                    }
                    ?>
                    <img src="<?php echo $profileImagePath; ?>" alt="Profile Image" id="profileImage">

                        <div class="image-overlay">
                            <span>Click to change profile picture</span>
                        </div>
                    </div>
                </div>
                <h1 class="profile-name"><?php echo htmlspecialchars($user['name'] ?? 'User Name'); ?></h1>
                <button class="edit-btn" onclick="openModal()">Edit Profile</button>
            </div>

            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['email'] ?? 'email@example.com'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['address'] ?? 'Not set'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data" id="updateForm">
                <div class="form-group">
                    <label for="imageUpload">Profile Picture</label>
                    <input type="file" id="imageUpload" name="profile_image" accept="image/*" onchange="previewImage(this);">
                    <img id="imagePreview" class="preview-image" style="display: none;">
                    
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                </div>
                <button type="submit" class="edit-btn">Save Changes</button>
                <button type="button" class="edit-btn" onclick="closeModal()" style="background: #666;">Cancel</button>
            </form>
        </div>
    </div>

    
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const profileImage = document.getElementById('profileImage');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    // Update main profile image preview
                    profileImage.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }


        // ... JavaScript sebelumnya ...
    </script>

    <script>
        function openModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('active');
        }

        function closeModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    
    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <?php if(isset($_GET['success'])): ?>
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notifikasi</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-success text-white">
                    Profile updated successfully!
                </div>
            </div>
        <?php elseif(isset($_GET['error'])): ?>
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notifikasi</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-danger text-white">
                    Error updating profile. Please try again.
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
