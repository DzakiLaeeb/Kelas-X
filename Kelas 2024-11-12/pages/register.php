<div class="register">
    <h1>Register</h1>
    <form style="display: flex; gap: 15px;" action="" method="post">
        <input type="email" name="email" required placeholder="Masukkan alamat Email">
        <input type="password" name="password" required placeholder="Masukkan password">
        <input type="submit" name="register" value="Register">
    </form>
</div>
<?php 
    if (isset($_POST["register"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        // echo "<br>";
        // echo $email;
        $sql = "INSERT INTO customer (email, password) VALUES ('$email', '$password')";
        mysqli_query($koneksi, $sql);
        header ("location:index.php?menu=login");
        echo $sql;
    }
?>