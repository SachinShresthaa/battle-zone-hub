<?php
session_start();
    include "Header.php";
    include "../connection.php";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="./CSS/adminPanel.css" rel="stylesheet">
</head>
<body>
<div class="main">
    <div class="nav-body">
        <nav class="nav-main">
                <div class="side-bar">
                    <a href="./index.php?dashboard">
                        <div class="nav-option">
                            <img src="./ASSETS/dashboard.png" alt="dashboard" class="option-icon">
                            <h3>Dashboard</h3>
                        </div>
                    </a>
                    <a href="./index.php?users">
                        <div class="nav-option">
                            <img src="./ASSETS/users.png" alt="users" class="option-icon">
                            <h3>Users</h3>
                        </div>
                    </a>
                    <a href="./index.php?tournaments">
                        <div class="nav-option">
                            <img src="./ASSETS/TOURNAMENT.png" alt="tournament" class="option-icon">
                            <h3>Tournaments</h3>
                        </div>
                    </a>
                    <a href="./index.php?room-cards">
                        <div class="nav-option">
                            <img src="./ASSETS/card.png" alt="room-cards" class="option-icon">
                            <h3>Room Cards</h3>
                        </div>
                    </a>
                    <a href="./index.php?leaderboard">
                        <div class="nav-option">
                            <img src="./ASSETS/Ranking.png" alt="ranking" class="option-icon">
                            <h3>Leaderboard</h3>
                        </div>
                    </a>
                    <a href="./index.php?sharelive">
                        <div class="nav-option">
                            <img src="./ASSETS/sharelive.png" alt="live" class="option-icon">
                            <h3>Share Live</h3>
                        </div>
                    </a>
                    <a href="../logout.php">
                        <div class="nav-option option6">
                            <img src="./ASSETS/logout.png" alt="Logout" class="option-icon">
                            <h3>Logout</h3>
                        </div>
                    </a>
                </div>
        </nav>
    </div>
    <div class="dashboard-main">
        <?php
        if (!isset($_GET['dashboard']) && !isset($_GET['users']) && !isset($_GET['tournaments']) && !isset($_GET['room-cards']) && !isset($_GET['leaderboard']) && !isset($_GET['sharelive'])) {
            include 'dashboard.php';
        }

        if (isset($_GET['dashboard'])) {
            include 'dashboard.php';
        }

        if (isset($_GET['users'])) {
            include 'manage_users.php';
        }

        if (isset($_GET['tournaments'])) {
            include 'manage_tournaments/manage_tournament.php';
        }

        if (isset($_GET['room-cards'])) {
            include 'room-cards.php';
        }

        if (isset($_GET['leaderboard'])) {
            include 'manage_leaderboard/manage_leaderBoard.php';
        }

        if (isset($_GET['sharelive'])) {
            include 'sharelive.php';
        }
        ?>
    </div>
</div>
</body>
</html>
<?php
    include "Footer.php";
?>
