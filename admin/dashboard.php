<?php
    include("../connection.php");

    $result = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
    $data = mysqli_fetch_assoc($result);
    $total_users = $data['total_users'];

    $resultTotalTournaments = mysqli_query($conn, "SELECT COUNT(*) AS total_tournaments FROM tournaments");
    $dataTotalTournaments = mysqli_fetch_assoc($resultTotalTournaments);
    $total_tournaments = $dataTotalTournaments['total_tournaments'];

    $resultLiveStreams = mysqli_query($conn, "SELECT COUNT(*) AS total_live_streams FROM youtube_lives");
    $dataLiveStreams = mysqli_fetch_assoc($resultLiveStreams);
    $total_live_streams = $dataLiveStreams['total_live_streams'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./CSS/dashboard.css">
</head>
<body>
<div class="container">
        <div class="head">
            <h1>DASHBOARD</h1>
        </div>
            <h2>Quick Access</h2>
            <ul>
                <li><a href="./adminPanel.php?tournaments">Add Tournaments</a></li>
                <li><a href="./adminPanel.php?room-cards">Provide Room Cards</a></li>
                <li><a href="./adminPanel.php?sharelive">Add Live Streams</a></li>
                <li><a href="./adminPanel.php?leaderboard">Update Leaderboard</a></li>
            </ul>
        <div class="status">
            <div class="status-item">
                <h3 style="padding-bottom:25px;">Total Users</h3>
                <div class="status-value">
                    <p><?php echo $total_users; ?></p>
                </div>
            </div>
            <div class="status-item">
                <h3 style="padding-bottom:10px;">Total<br>Tournaments</h3>
                <div class="status-value">
                    <p><?php echo $total_tournaments; ?></p>
                </div>
            </div>
            <div class="status-item">
                <h3 style="padding-bottom:10px;">Total<br>Live Streams</h3>
                <div class="status-value">
                    <p><?php echo $total_live_streams; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
