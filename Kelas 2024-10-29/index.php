<?php 

    $sekolah = [
        "TK Thoriqussalam",
        "Sd Khadijah 3", 
        "MTS Bilngual", 
        "SMKN 2 Buduran"
    ];

    $sekolahs = [
        "TK" => "TK Thoriqussalam", 
        "SD" => "SD Khadijah 3", 
        "SMP" => "MTS Bilingual", 
        "SMK" => "SMKN 2 Buduran",
        "PT" => "Universitas Negeri Surabaya"
    ];

    $skills = [
        "c++" => "Expert", 
        "HTML" => "newbie", 
        "css" => "newbie", 
        "php" => "intermediate", 
        "javascript" => "intermediate"
    ];

    $identitas = [
        "nama" => "Dzaki Alfredo Sutanto",
        "alamat" => "King Safira",
        "email" => "dzakialfredosutanto7@gmail.com",
        "fb" => "dzexz",
        "tiktok" => "ELVE Laeeb",
        "ig" => "Laeeb Verro"
    ];

    $hobi = [
        "ngoding",
        "main valo",
        "mancing",
        "sepeda",
        "membaca"
    ];

//     echo $sekolah[0];
//     echo "<br>";
//     echo $sekolahs["TK"];
//     echo "<br>";
//     echo $sekolah[1];
//     echo "<br>";
//     echo $sekolahs["SD"];
//     echo "<br>";

//     for ($i=0; $i < 4; $i++) { 
//         echo $sekolah[$i];
//         echo "<br>";
//     }

//     foreach ($sekolahs as $key) {
//         echo $key;
//         echo "<br>";
//     }

//     foreach ($sekolahs as $key => $value) {
//         echo $key;
//         echo "=";
//         echo $value;
//         echo "<br>";
//     }

//     foreach ($variable as $key => $value) {
//         echo $key;
//         echo "=";
//         echo $value;
//         echo "<br>";
//     }

//     foreach ($identitas as $key => $value) {
//         echo $key;
//         echo "=";
//         echo $value;
//         echo "<br>";
//     }
// 

if (isset($_GET["menu"])) {
    $menu = $_GET["menu"];
    echo $menu;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <hr>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="?menu=cv">CV</a></li>
        <li><a href="?menu=project">project</a></li>
        <li><a href="?menu=kontak">Kontak</a></li>
    </ul>
    <h2>Riwayat Sekolah</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Jenjang</th>
                <th>Nama Sekolah</th>
            </tr>
        </thead>
        <tbody>
             <?php foreach ($sekolahs as $key => $value) {
                echo "<tr>";
                echo "<td>";
                echo $key;
                echo "</td>";
                echo "<td>";
                echo $value;
                echo "</td>";
                echo "</tr>";
             } ?>
        </tbody>
    </table>
    <hr>
    <h2>Skills</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Skill</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>

                <thead>
                    <tr>
                        <td>skill</td>
                        <td>level</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($skills as $key => $value) {
                    ?>
                    <tr>
                        <td><?=$key ?></td>
                        <td><?=$value ?></td>
                    </tr>

                    <?php 
                    
                    }
                    
                    ?>
                </tbody>

            <?php
                
            ?>
        </tbody>
    </table>
<hr>
<h2>identitas</h2>
<table border="1">
    <thead>
        <tr>
            <th>Identitas</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        
            foreach ($identitas as $key => $value) {
                echo "<tr>";
                echo "<td>";
                echo $key;
                echo "</td>";
                echo "<td>";
                echo $value;
                echo "</td>";
                echo "</tr>";
            }

        ?>
    </tbody>
</table>
<hr>
<h2>Hobi</h2>
<ol>
    <?php 

        foreach ($hobi as $key) {
        ?>

        <li><?= $key ?></li>

        <?php
        }
        ?>
</ol>
</body>
</html>