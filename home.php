<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="./CSS/home.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <?php
            include "HeaderWithProfile.php"
        ?>
        <div class="Body">
            <h2>GAMES</h2>
            <p>Choose your favorite game, enter tournaments, <br>showcase your skills, and claim your rewards!</p>
            <div class="games">
                    <a href="FreeFire.php">
                        <img src="./ASSETS/home-ff.png" alt="Free Fire">
                        <h3>FREE FIRE</h3>
                    </a>
                    <a href="Pubg.php">
                        <img src="./ASSETS/home-pubg.png" alt="PUBG">
                        <h3>PUBG</h3>
                    </a>
            </div>
        </div>
        <?php
            include "Footer.php"
        ?>
    </div>
</body>
</html>