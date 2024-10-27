<?php

    $judul = "Curriculum Vitae";

    $sekolah = "Sekolah";
    $sekolahs = "SD Khadijah, MTS Bilingual, SMKN 2 Buduran";

    $identitas = "Identitas";
    $identitases = [
        "Dzaki Alfredo Sutanto",
        "JL. Sepande",
        "dzakialfredosutanto7@gmail.com",
        "@dzaki"
    ];
    
    $skills = [ 
        "HTML Expert",
        "CSS Expert",
        "PHP Intermediate",
        "JavaScript Newbie"
    ];

    $sekolahs = [
        "TK Thoriqussalam",
        "SD Khadijah 3",
        "MTS Bilingual",
        "SMK Negeri 2 Buduran"
    ];

    $hobi = "Hobi";
    
    $hobies = [
        "Ngoding",
        "Main Valo",
        "Buat kerajinan"
    ];

    $copyright = "Copyright © BY Dzaki 2024";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tugas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            background-color: rgb(24, 24, 24);
            /* overflow-y: hidden; */
        }

        .kelas1 {
            font-family: Verdana, Geneva, Tahoma, sans-serif; 
            text-align: center;
            padding-top: 20px;
        }

        .container {
            background-color: #af9c8d;
            box-shadow: 10px 10px 5px rgb(49, 49, 49);
            height: 720px;
            width: 550px;
            margin-top: 50px;
            margin-left: 380px;
            border-radius: 25px;
            border: none;
        }

        .identitas {
            background-color: #c9b09a;
            box-shadow: 1px 1px 5px rgb(70, 69, 69);
            transition: background-color 0.3s ease, height 0.8s, width 0.8s;
            width: 480px;
            height: 200px;
            margin-top: 50px;
            margin-left: 35px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            border-radius: 25px;
        }

        .identitas:hover {
            background-color: #c0a690;
            width: 485px;
            height: 210px;
        }


        .skill {
            background-color: #c9b09a;
            box-shadow: 1px 1px 5px rgb(70, 69, 69);
            transition: background-color 0.3s ease, height 0.8s, width 0.8s;
            width: 300px;
            height: 150px;
            margin-top: 25px;
            margin-left: 25px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            border-radius: 25px;
        }

        .skill:hover {
            background-color: #c0a690;
            width: 310px;
            height: 159;
        }


        .sekolah {
            background-color: #c9b09a;
            box-shadow: 1px 1px 5px rgb(70, 69, 69);
            transition: background-color 0.3s ease, transform 0.8s;
            transform-origin: bottom;
            width: 320px;
            height: 150px;
            margin-top: 25px;
            margin-left: 200px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            border-radius: 25px;
        }

        .sekolah:hover {
            background-color: #c0a690;
            transform: scaleX(1.05);
        }


        .hobi {
            background-color: #c9b09a;
            box-shadow: 1px 2px 5px rgb(70, 69, 69);
            transition: background-color 0.8s ease, transform 0.8s;
            width: 180px;
            height: 150px;
            margin-top: -325px;
            margin-left: 350px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            border-radius: 25px;
        }

        .hobi:hover {
            background-color: #c0a690;
            transform: scale(1.05);
        }

        .bywhat {
            width: 150px;
            height: 135px;
            background-color: #ab8c77;
            transition: background-color 0.8s, transform 0.8s, box-shadow 0.8s;
            margin-top: 30px;
            margin-left: 25px;
            border-radius: 20px;
        }

        .bywhat:hover {
            background-color: #c0a690;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="kelas1">
            <h1 style="color: white; "><?= $judul ?></h1>
        </div>
        <div class="identitas">
            <table style="margin-top: 35px;">
                <thead>
                    <tr>
                        <th style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><strong>Identitas</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Nama :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $identitases[0] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Alamat :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $identitases[1] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Email :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $identitases[2] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">add :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $identitases[3] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="skill">
            <table style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><strong>My Skills</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Kemampuan :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $skills[0] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Kemampuan :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $skills[1] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Kemampuan :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $skills[2] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Kemampuan :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $skills[3] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="sekolah">
            <table style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><strong>Sekolah</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">TK :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $sekolahs[0] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">SD :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $sekolahs[1] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">SMP :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $sekolahs[2] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">SMK :</td>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $sekolahs[3] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hobi">
            <table style="margin-top: 25px;">
                <thead>
                    <tr>
                        <th style="font-family: Verdana, Geneva, Tahoma, sans-serif; ">Hobi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $hobies[0] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $hobies[1] ?></td>
                    </tr>
                    <tr>
                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; "><?= $hobies[2] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bywhat" style="color: #e7d4c2; font-size: 15px; text-align: center;">
            <p style="padding-top: 50px; margin: 5px;"><?php echo $copyright; ?></p>
        </div>
    </div>
</body>
</html>