<?php 
    $sql = "SELECT * FROM jadwal";
    echo $sql;

    $hasil = mysqli_query($koneksi, $sql);
    while ($row = mysqli_fetch_array($hasil)) {

?>

    <div class="jadwal">
        <h2><?php echo $row[1] ?></h2>
        <p><?php echo $row[2] ?></p>
    </div>

<?php 
    }
?>