<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .register-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h2 {
            font-size: 1.8rem;
            color: #333;
        }

        .form-control {
            border-radius: 25px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 1rem;
            border: 2px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: none;
        }

        .btn-register {
            background-color: #3498db;
            color: white;
            border-radius: 25px;
            padding: 12px;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-register:hover {
            background-color: #2980b9;
        }

        .text-center {
            margin-top: 20px;
        }

        .text-center a {
            color: #3498db;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Registrasi TokoOnline</h2>
        </div>
        <form action="#" method="POST">
            <input type="text" class="form-control" placeholder="Nama Lengkap" required>
            <input type="email" class="form-control" placeholder="Email" required>
            <input type="password" class="form-control" placeholder="Password" required>
            <input type="password" class="form-control" placeholder="Konfirmasi Password" required>
            <button type="submit" class="btn-register">Daftar</button>
        </form>
        <div class="text-center">
            <p>Sudah punya akun? <a href="#">Masuk di sini</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>