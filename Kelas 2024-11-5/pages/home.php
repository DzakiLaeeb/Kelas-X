<?php 
    $sql = "SELECT * From home";
    echo $sql;
    $hasil = mysqli_query($koneksi, $sql);
    while ($row = mysqli_fetch_array($hasil)) {
    
?>

    <div class="home">
        <h2 style="margin-left: 20px; margin-top: 20px;"><?php echo $row[1]; ?></h2>
        <p style="margin-left: 20px; padding-bottom: 20px;"><?php echo $row[2]; ?></p>
    </div>

<?php 
    }
?>