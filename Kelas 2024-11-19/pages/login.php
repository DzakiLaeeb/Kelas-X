<div class="login">
    <h1>Login</h1>
    <form style="display: flex; gap: 8px;" action="" method="post">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <input type="submit" name="login" value="login">
    </form>
</div>

<?php

    if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM customer WHERE email = '$email' AND password = '$password'";
        $hasil = mysqli_query($koneksi, $sql);

        if (!$hasil) {
            echo "Error: " . mysqli_error($koneksi);
            exit;
        }

        $baris = mysqli_num_rows($hasil);

        if ($baris == 0) {
            echo '<h2>Email atau Password salah</h2>';
        } else {
            $_SESSION["email"] = $email;
            header("location: index.php?menu=produk.php");
            exit;
        }
    }

?>