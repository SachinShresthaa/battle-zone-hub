<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUBG PAGE</title>
    <link href="Pubg.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <?php
            include "HeaderWithProfile.php"
        ?>
        <div class="Body">
            <div class="BannerPUBG">
                <img src="./PICTURE AND LOGO/pubg background.png" alt="BannerFF">
            </div>
            <div class="PUBG-main">
                <img src="./PICTURE AND LOGO/home pubg.png" alt="main-img">
                <h1>PUBG : PlayerUnknown's Battlegrounds </h1>
            </div>
            <div class="properties">
                <div class="tournaments">
                    <img src="./PICTURE AND LOGO/TOURNAMENT.png" alt="tournaments">
                    <h1>Tournaments</h1>
                </div>
                <div class="LeaderBoard">
                    <img src="./PICTURE AND LOGO/Ranking 1.png" alt="LeaderBoard" style="width: 120px; height: 90px;">
                    <h1>LeaderBoard</h1>
                </div>
                <div class="Live">
                    <img src="./PICTURE AND LOGO/YOUTUBE LIVE.png" alt="live" style="width: 190px; margin-top: 10px; " >
                    <h1 style="padding-bottom: 10px;">YouTube Live</h1>
                </div>
                <div class="matches">
                    <img src="./PICTURE AND LOGO/MATCHES.png" alt="MATCHES">
                    <h1>My Matches</h1>
                </div>
            </div>
        </div>
        <?php
            include "Footer.php"
        ?>
    </div>
</body>
</html>