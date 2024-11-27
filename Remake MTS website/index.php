<?php

    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $database = "remake website";

    $koneksi = mysqli_connect($host, $user, $password, $database);

    $list1 = [
        "BERANDA",
        "PROFIL",
        "SISTEM INFORMASI MADRASAH",
        "PENGUMUMAN PPDB",
        "LITERASI"
    ];

    $list2 = [
        "GALERI MADRASAH",
        "SOSIAL MEDIA OFFICIAL"
    ];

    $tulisan1besar = "CAMBRIDGE <br> INTERNATIONAL <br> ASSESMENT";
    $tulisan2kecil = "Full Day & Boarding School";
    $button1 = "DAFTAR PPDB 2023-2024";
    $MmM = "Mengapa memilih MTsB?";
    $ik = "Informasi Kami";
    $lmk = "Lokasi Madrasah Kami";
    $lmkisi = "Jl. Jenggolo No.53, Kelurahan Pucang, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61219";
    $hk = "Hubungi Kami";
    $hkisi1 = "Office  +(031) 99705746";
    $hkisi2 = "Office Mobile +62 895-0805-1508";
    $hkisi3 = "Email : mtsbilingualmuslimatnu@gmail.com";
    $hklk = "images/lokasinya mtsb.png";
    $kpa = "Kirim Pesan Anda";
    $copyright = "Copyright 2024 | By ";
    $dzexz = "Dzexz";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTS Bilingual Remake by Dzexz</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #c9eeb2;
            margin: 0;
            /* overflow-x: hidden; */
        }

        .navbar {
            width: 100%;
            height: 120px;
            position: fixed;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .navbar.scrolled {
            background-color: white;
            box-shadow: rgba(0, 0, 0, 0.288) 5px 2px 8px;
        }

        .nav1 {
            display: flex;
            gap: 60px;
            list-style: none;
            margin: 0;
            padding: 10px 20px;
            color: white;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 12px;
            position: absolute;
            margin-top: 35px;
            margin-left: 450px;
        }

        .nav1 li.scrolled {
            transform: translateY(-18px);
            color: black;
        }
        
        .nav2 {
            display: flex;
            gap: 60px;
            list-style: none;
            margin: 0;
            padding: 10px 20px;
            color: white;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 12px;
            position: absolute;
            margin-top: 85px;
            margin-left: 835px;
        }

        .nav2 li.scrolled {
            transform: translateY(-18px);
            color: black;
        }


        .gambar1 img {
            width: 1280px;
            height: 800px;
        }

        .logo img {
            width: 50px;
            height: 50px;
            transition: 0.8s ease;
            cursor: pointer;
        }

        .logo img:hover {
            filter: grayscale(20%) brightness(80%);
        }

        .logo {
            position: fixed;
            display: flex;
            top: 50px;
            left: 62px;
        }

        .logo.scrolled {
            margin-top: -8px;
            margin-left: 10px;
        }

        .logo img.scrolled {
            width: 35px;
            height: 35px;
        }

        .MBMN {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            margin-top: 2px;
            margin-left: 7px;
        }

        .CIAP {
            color: grey;
            font-size: 9px;
            margin-top: 22px;
            margin-left: -155px;
        }
        
        .MBMN, .CIAP {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .MBMN.scrolled, .CIAP.scrolled {
            opacity: 1;
        }


        .tulisan1besar h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 65px;
            line-height: 98px;
            color: white;
            font-family: Arial, Helvetica, sans-serif;
            justify-content: center;
            text-align: center;
        }

        .tulisan2kecil p {
            position: absolute;
            top: 88%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            line-height: 98px;
            color: white;
            justify-content: center;
            text-align: center;
        }

        .button1 {
            width: 220px;
            height: 55px;
            padding: 10px;
            background-color: #58bc14;
            border: none;
            border-radius: 8px;
            position: absolute;
            top: 110%;
            left: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
        }

        .container {
            height: 1000px;
        }

        .container2 {
            display: flex;
            justify-content: center;
            background-color: #c9eeb2;
            width: 100%;
            height: 1500px;
            margin-top: -10px;
        }

        .containerdalam {
            background-color: white;
            box-shadow: rgba(0, 0, 0, 0.164) 8px 8px 18px;
            width: 1200px;
            height: 5988px;
            margin-top: -80px;
            margin-left: 30px;
            border-radius: 15px;
        }

        .containerdalam h2 {
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            font-size: 38px;
            margin-top: 120px;
            line-height: 25px;
        }

        .judul1 {
            width: 100%;
        }

        .pelajaran1 {
            display: flex;
            gap: 225px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 175px;
            margin-left: 185px;
        }

        .deskripsi1 {
            margin-top: 55px;
            color: grey;
            margin-left: -385px;
            width: 255px;
        }

        .pelajaran2 {
            display: flex;
            gap: 325px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 185px;
            margin-left: 155px;
        }

        .deskripsi2 {
            display: flex;
            color: grey;
            padding: 8px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            gap: 2px;
            margin-top: -8px;
            /* margin-left: -385px; */
            text-align: center;
        }

        .guru1 {
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 175px;
            margin-left: -735px;
        }

        .gurudeskripsi1 {
            margin-top: 48px;
            color: grey;
            margin-left: -285px;
            width: 255px;
        }
    
        .guru2 {
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: -250px;
            margin-left: 35px;
        }

        
        .gurudeskripsi2 {
            margin-top: 48px;
            text-align: center;
            color: grey;
            margin-left: -388px;
            width: 355px;
        }

            
        .guru3 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: -578px;
            margin-left: 755px;
        }

        
        .gurudeskripsi3 {
            margin-top: 58px;
            text-align: center;
            color: grey;
            margin-left: -428px;
            width: 355px;
        }
            
        .guru4 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 228px;
            margin-left: -288px;
        }

        
        .gurudeskripsi4 {
            position: relative;
            right: 400px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }
                    
        .guru5 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            top: -512px;
            right: -445px;
            margin-left: -388px;
        }
        
        .gurudeskripsi5 {
            position: relative;
            right: 388px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }
                 
        .guru6 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            top: -869px;
            right: -800px;
            margin-left: -388px;
        }
        
        .gurudeskripsi6 {
            position: relative;
            right: 388px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }

                
        .gurudeskripsi5 {
            position: relative;
            right: 388px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }
                 
        .guru7 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            top: -700px;
            right: -50px;
            margin-left: -388px;
        }
        
        .gurudeskripsi7 {
            position: relative;
            right: 388px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }
                         
        .guru8 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            top: -1170px;
            right: -430px;
            margin-left: -388px;
        }

        
        .gurudeskripsi8 {
            position: relative;
            right: 358px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }
                      
        .guru9 {
            position: relative;
            display: flex;
            text-align: center;
            justify-content: center;
            gap: 85px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            top: -1499.9px;
            right: -788px;
            margin-left: -388px;
        }

        
        .gurudeskripsi9 {
            position: relative;
            right: 358px;
            margin-top: 58px;
            color: grey;
            width: 355px;
        }

        .judulberita{
            margin-top: -1385px;
        }

        .judulberita p {
            color: grey;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            text-align: center;
        }

        .containerberita1 {
            display: flex;
        }

        .containerberita2 {
            display: flex;
        }

        .containerinformasi {
            background-color: black;
            width: 100%;
            height: 895px;
            margin-top: 155px;

        }

        .kpa {
            position: relative;
            background-color: green;
            width: 422px;
            height: 82px;
            border-radius: 18px;
            margin-top: -1358px;
            margin-left: 700px;
            box-shadow: grey 5px 5px 28px;
            z-index: 8;
        }

        .boxkpa {
            position: relative;
            top: -755px;
            background-color: white;
            width: 485px;
            height: 555px;
            border-radius: 18px;
            margin-left: 670px;
            z-index: 5;
        }

        .form1 {
            display: flex;
        }

        .copyright {
            position: relative;
            width: 1888px;
            height: 85px;
            background-color: grey;
            top: -18px;
            left: -255px;
            background-color: black;
        }

        .cgambar1 {
            position: relative;
            top: 155px;
            margin-left: 152px;
        }

        .cg2 {
            margin-left: 245px;
        }

        .cg3 {
            margin-left: 285px;
        }

        .cgambar2 {
            position: relative;
            top: 175px;
            margin-left: 152px;
        }

        .cg5 {
            margin-left: 285px;
        }

        .cg6 {
            margin-left: 285px;
        }

        .ggambar {
            display: flex;
            position: relative;
            top: -555px;
            margin-left: 175px;
            margin-bottom: 355px;
        }

        .g1 {
            position: relative;
            top: -685px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 155px;
        }

        
        .g2 {
            position: relative;
            top: -685px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 295px;
        }

        
        .g3 {
            position: relative;
            top: -685px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 285px;
        }
        
        .ggambar {
            display: flex;
            position: relative;
            top: -555px;
            margin-left: 175px;
            margin-bottom: 355px;
        }

        .g4 {
            position: relative;
            top: -1555px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 185px;
        }

        
        .g5 {
            position: relative;
            top: -1555px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 295px;
        }

        
        .g6 {
            position: relative;
            top: -1555px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 285px;
        }

                
        .ggambar {
            display: flex;
            position: relative;
            top: -555px;
            margin-left: 175px;
            margin-bottom: 355px;
        }

        .g7 {
            position: relative;
            top: -2255px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 185px;
        }

        
        .g8 {
            position: relative;
            top: -2255px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 295px;
        }

        
        .g9 {
            position: relative;
            top: -2255px;
            width: 90px;
            height: 100px;
            border-radius: 55px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.5);
            margin-left: 255px;
        }
    </style>
</head>
<body>
    <div class="navbar" id="navbar">
        <div class="logo" id="logo">
            <a href="index.php"><img src="images/logomtsbilingual.png" alt=""></a>
            <p class="MBMN">MTs Bilingual Muslimat NU</p>
            <p class="CIAP">Cambridge International Assesment</p>
        </div>
        <ul class="nav1" id="nav1">
            <li><?php echo $list1[0] ?></li>
            <li><?php echo $list1[1] ?></li>
            <li><?php echo $list1[2] ?></li>
            <li><?php echo $list1[3] ?></li>
            <li><?php echo $list1[4] ?></li>
        </ul>
        <ul class="nav2" id="nav2">
            <li><?php echo $list2[0] ?></li>
            <li><?php echo $list2[1] ?></li>
        </ul>
    </div>
    
    <div class="container1">
        <div class="gambar1">
            <img src="images/gambar1container1.png" alt="">
            <div class="tulisan1besar">
                <h1><?php echo $tulisan1besar ?></h1>
            </div>
            <div class="tulisan2kecil">
                <a href="href="https://ppdb.mtsb.sch.id/"><p><?php echo $tulisan2kecil ?></p></a>
            </div>
            <a style="text-decoration: none" href="https://ppdb.mtsb.sch.id/">
                <button class="button1"><p style="color: white; margin: -5px;">
                    <strong><?php echo $button1 ?></strong></p>
                </button>
            </a>
        </div>
    </div>
    <div class="container2">
        <div class="containerdalam">
            <div class="judul1">
                <?php
                    $sql = "SELECT * From home LIMIT 1";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h2 style="text-align: center;"><?php echo $row[1] ?></h2>
                <p style="justify-content: center; width: 855px; text-align: center; font-size: 18px; line-height: 28px; color: grey; font-family: Verdana, Geneva, Tahoma, sans-serif; margin-left: 185px; "><?php echo $row[2] ?></p>
                <?php
                    }
                ?>
            </div>
            <div class="cgambar1">
                <img class="cg1" src="images/icon1.png" alt="">
                <img class="cg2" src="images/icon2.png" alt="">
                <img class="cg3" src="images/icon3.png" alt="">
            </div>
            <div class="pelajaran1">
                <?php
                    $sql = "SELECT * From home LIMIT 1, 3";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="deskripsi1">
                    <p style="width: 250px; text-align: center; margin-right: 85px;"><?php echo $row[2] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="cgambar2">
                <img class="cg4" src="images/icon4.png" alt="">
                <img class="cg5" src="images/icon5.png" alt="">
                <img class="cg6" src="images/icon6.png" alt="">
            </div>
            <div class="pelajaran2">
                <?php
                    $sql = "SELECT * From home LIMIT 4, 6";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <?php
                    }
                ?>
            </div>
            <div class="deskripsi2">
                <?php
                    $sql = "SELECT * From home LIMIT 4, 6";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                    <p><?php echo $row[2] ?></p>
                <?php
                    }
                ?>
            </div>
            <div class="judul2">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h2 style="text-align: center; margin-top: 258px; font-size: 45px; "><?php echo $row[1] ?></h2>
                <p style="justify-content: center; width: 855px; text-align: center; font-size: 18px; line-height: 28px; color: grey; font-family: Verdana, Geneva, Tahoma, sans-serif; margin-left: 185px; "><?php echo $row[3] ?></p>
                <?php
                    }
                ?>
            </div>
            <div class="guru1">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 1";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi1">
                    <p style="text-align: center;"><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru2">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 2";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi2">
                    <p style="text-align: center;"><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru3">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 3";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi3">
                    <p style="text-align: center;"><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="ggambar1">
                <img class="g1" src="images/guru1.webp" alt="">
                <img class="g2" src="images/guru2.webp" alt="">
                <img class="g3" src="images/guru3.webp" alt="">
            </div>
            <div class="guru4">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 4";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi4">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru5">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 5";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi5">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru6">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 6";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi6">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="ggambar2">
                <img class="g4" src="images/guru4.webp" alt="">
                <img class="g5" src="images/guru5.webp" alt="">
                <img class="g6" src="images/guru6.webp" alt="">
            </div>
            <div class="guru7">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 7";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi7">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru8">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 8";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi8">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="guru9">
                <?php
                    $sql = "SELECT * From testimoni LIMIT 1 OFFSET 9";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h4><?php echo $row[1] ?></h4>
                <div class="gurudeskripsi9">
                    <p><?php echo $row[2] ?></p>
                    <p><?php echo $row[3] ?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="ggambar3">
                <img class="g7" src="images/guru7.webp" alt="">
                <img class="g8" src="images/guru8.webp" alt="">
                <img class="g9" src="images/guru9.webp" alt="">
            </div>
            <div class="judulberita">
                <?php 
                    $sql = "SELECT * FROM berita LIMIT 1 OFFSET 6";
                    $hasil = mysqli_query($koneksi, $sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                ?>
                <h2><?= $row[1] ?></h2>
                <p><?= $row[3] ?></p> 
                <?php 
                    }
                ?>
            </div>
            <div class="containerberita1">
                <div class="berita1">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 0";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: purple "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/05/11/implementasi-p5-p2ra-mts-bilingual/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
                <div class="berita2">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 1";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: blue "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/05/07/monitoring-asesmen-madrasah-2024/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
                <div class="berita3">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 2";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: red "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/05/06/kanwil-kemenag-prov-jatim-monitoring-pelaks/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
            </div>
            <div class="containerberita2">
                <div class="berita1">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 3";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: purple "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/04/28/kepala-madrasah-menerima-penghargaan-tingkat-asean-2024/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
                <div class="berita2">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 4";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: blue "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/04/22/kartini-2024/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
                <div class="berita3">
                    <?php 
                        $sql = "SELECT * FROM berita LIMIT 1 OFFSET 5";
                        $hasil = mysqli_query($koneksi, $sql);
                        while ($row = mysqli_fetch_array($hasil)) {
                    ?>
                    <img style="width: 350px; height: 230px; margin-top: 55px; margin-left: 35px; border-radius: 5px; box-shadow: grey 5px 5px 8px" src="images/<?php echo $row['gambar']; ?>" alt="">
                    <p style="padding-left: 35px; padding-top: 8px; font-size: 12px; font-family: Verdana, Geneva, Tahoma, sans-serif; color: red "><?= $row[2] ?></p>
                    <h4 style="margin-left: 35px; font-size: 20px; width: 355px; margin-top: -1px"><?= $row[3] ?></h4>
                    <p style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px; color: grey; line-height: 22px; width: 355px; padding-left: 35px"><?= $row[4] ?><a href="https://mtsb.sch.id/home/index.php/2024/04/06/berkah-ramadhan-memahami-5-keutamaan-bulan-penuh-berkah/"style="text-decoration: none;"> Read more.....</a></p>
                    <?php 
                        }
                    ?>
                </div>
            </div>
            <div class="containerinformasi">
                <h1 style="color: white; font-size: 38px; padding-top: 85px; padding-left: 35px"><?= $ik ?></h1>
                <h1 style="color: white; font-size: 22px; padding-top: 35px; padding-left: 85px"><?= $lmk ?></h1>
                <p style="width: 355px; color: white; padding-left: 85px; "><?= $lmkisi ?></p>
                <h1 style="color: white; font-size: 22px; padding-top: 25px; padding-left: 85px"><?= $hk ?></h1>
                <p style="width: 355px; color: white; padding-left: 85px; "><?= $hkisi1 ?></p>
                <p style="width: 355px; color: white; padding-left: 85px; "><?= $hkisi2 ?></p>
                <p style="width: 355px; color: white; padding-left: 85px; "><?= $hkisi3 ?></p>
                <img style="width: 300px; height: 400px; margin-left: 85px" src="<?= $hklk ?>" alt="">
                <div class="boxkpa">
                    <div class="form1">
                        <form action="">
                            <input style="margin-top: 155px; margin-left: 55px; border: none; outline: none; " type="text" name="Tulis nama Kamu" placeholder="Tulis Nama Kamu">
                            <hr style="width: 158px; margin-top: 18px; margin-left: 58px">
                        </form>
                        <form action="">
                            <input style="margin-top: 155px; margin-left: 55px; border: none; outline: none; " type="text" name="Tulis alamat email Kamu" placeholder="Tulis alamat email Kamu">
                            <hr style="width: 158px; margin-top: 18px; margin-left: 58px">
                        </form>
                    </div>
                    <form action="">
                            <input style="margin-top: 8px; margin-left: 55px; border: none; outline: none; height: 85px" type="text" name="Tulis pesan Kamu" placeholder="Tulis pesan Kamu">
                            <hr style="width: 158px; margin-top: 158px; margin-left: 58px">
                    </form>
                    <button style="margin-left: 285px; width: 158px; height: 58px; border-radius: 8px; border: none; color: white; cursor: pointer; background-color: green">Kirim Pesan</button>
                </div>
                <div class="kpa">
                    <strong><p style="padding: 33px; color: white; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 18px; padding-left: 118px;"><?= $kpa ?></p></strong>
                </div>
            </div>
            <div class="copyright">
                <p style="text-align: center; color: white; font-family: Verdana, Geneva, Tahoma, sans-serif; padding-right: 185px"><?php echo $copyright ?><a style="text-decoration: none; color: gold"href="index.html"><?php echo $dzexz ?></a></p>
                <hr style="width: 1055px; margin-left: 288px">
            </div>
        </div>
    </div>

    <script>
            
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const logo = document.querySelector('.logo');
            const logoImg = document.querySelector('.logo img');
            const nav1 = document.querySelectorAll('.nav1 li');
            const nav2 = document.querySelectorAll('.nav2 li');
            const MBMNText = document.querySelector('.MBMN');
            const CIAPText = document.querySelector('.CIAP');

            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                logo.classList.add('scrolled');
                logoImg.classList.add('scrolled');
                MBMNText.classList.add('scrolled');
                CIAPText.classList.add('scrolled');
                nav1.forEach(link => link.classList.add('scrolled'));
                nav2.forEach(link => link.classList.add('scrolled'));
            } else {
                navbar.classList.remove('scrolled');
                logo.classList.remove('scrolled');
                logoImg.classList.remove('scrolled');
                MBMNText.classList.remove('scrolled');
                CIAPText.classList.remove('scrolled');
                nav1.forEach(link => link.classList.remove('scrolled'));
                nav2.forEach(link => link.classList.remove('scrolled'));
            }
        });

    </script>
</body>
</html>
