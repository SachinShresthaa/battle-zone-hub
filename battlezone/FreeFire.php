<?php
require_once ("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FREE FIRE PAGE</title>
    <link href="./CSS/FreeFire.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <?php
            include "HeaderWithProfile.php"
        ?>
        <div class="Body">
            <div class="BannerFF">
                <img src="./ASSETS/ff-background.png" alt="BannerFF">
            </div>
            <div class="FF-main">
                <img src="./ASSETS/home-ff.png" alt="main-img">
                <h1>FREE FIRE</h1>
            </div>
            <div class="properties">
                <div class="tournaments">
                    <a href="tournaments.php?category=freefire">
                        <img src="./ASSETS/TOURNAMENT.png" alt="tournaments">
                        <h1>Tournaments</h1>
                    </a>
                </div>
                    <div class="LeaderBoard">
                        <a href="LeaderBoard.php">
                            <img src="./ASSETS/Ranking.png" alt="LeaderBoard" style="width: 120px; height: 90px;">
                            <h1>LeaderBoard</h1>
                        </a>
                    </div>
                <div class="Live">
                    <a href="sharelive.php?category=freefire"> <!-- Filter to only show FreeFire -->
                        <img src="./ASSETS/YOUTUBE-LIVE.png" alt="live" style="width: 190px; " >
                        <h1 style="padding-bottom:20px;">YouTube Live</h1>
                    </a>
                </div>
                <div class="matches">
    <a href="myMatches.php?category=freefire">
        <img src="./ASSETS/MATCHES.png" alt="MATCHES">
        <h1>My Matches</h1>
    </a>
</div>

            </div>
        </div>
        <?php
            include "Footer.php"
        ?>
    </div>
</body>
</html>
