<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUBG PAGE</title>
    <link href="./CSS/Pubg.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <?php
            include "HeaderWithProfile.php";

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "battlezonehub";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetching PUBG room details
            $rooms = [];
            $sql = "SELECT room_id, room_password FROM room_details WHERE category = 'pubg'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rooms[] = $row;
                }
            } else {
                $rooms = [];
            }
        ?>
        <div class="Body">
            <div class="BannerPUBG">
                <img src="./ASSETS/pubg-background.png" alt="BannerPUBG">
            </div>
            <div class="PUBG-main">
                <img src="./ASSETS/home-pubg.png" alt="main-img">
                <h1>PUBG : PlayerUnknown's Battlegrounds</h1>
            </div>
            <div class="properties">
                <div class="tournaments">
                    <a href="tournaments.php?category=pubg">
                        <img src="./ASSETS/TOURNAMENT.png" alt="tournaments">
                        <h1>Tournaments</h1>
                    </a>
                </div>
                <div class="LeaderBoard" id="leader_board">
                    <a href="LeaderBoard.php">
                        <img src="./ASSETS/Ranking.png" alt="LeaderBoard" style="width: 120px; height: 90px;">
                        <h1>LeaderBoard</h1>
                    </a>
                </div>
                <div class="Live">
                    <a href="sharelive.php?category=pubg"> <!-- Link to Live Stream Page -->
                        <img src="./ASSETS/YOUTUBE-LIVE.png" alt="live" style="width: 190px;">
                        <h1 style="padding-bottom:20px;">YouTube Live</h1>
                    </a>
                </div>
                <div class="matches">
                    <a href="myMatches.php">
                        <img src="./ASSETS/MATCHES.png" alt="MATCHES">
                        <h1>My Matches</h1>
                    </a>
                </div>
            </div>
        </div>
        <?php
            include "Footer.php";
        ?>
    </div>
</body>
</html>
