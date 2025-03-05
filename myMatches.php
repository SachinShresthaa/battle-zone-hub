<?php
include 'connection.php';
include "headerwithprofile.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category = isset($_GET['category']) ? $_GET['category'] : 'pubg';

if ($category == 'pubg') {
    $query = "SELECT t.name AS tournament_name, t.date, t.time, t.thumbnail
              FROM pubg_team_registration r
              INNER JOIN tournaments t ON r.tournament_id = t.id
              WHERE r.user_id = ?
              ORDER BY t.date DESC
              LIMIT 1";
} else {
    $query = "SELECT t.name AS tournament_name, t.date, t.time, t.thumbnail
              FROM ff_team_registration r
              INNER JOIN tournaments t ON r.tournament_id = t.id
              WHERE r.user_id = ?
              ORDER BY t.date DESC
              LIMIT 1";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Matches</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Jacques Francois;
            color: white;
        }

        body {
            background-color: black;
        }
        h2 {
            font-size: 36px;
            margin-bottom: 30px;
            margin-top: 50px;
            padding-left: 75px;
        }

        /* Tournament Section */
        .container {
            padding-left: 500px;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        /* Tournament Card */
        .card {
            display: flex;
            align-items: flex-start;
            background: #2E2E2E;
            border: 1px solid rgb(82, 80, 80);
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 700px;
        }

        /* Left Side Image */
        .leftSide {
            width: 350px;
            height: 220px;
            overflow: hidden;
            border-radius: 8px;
            background-color: #1c1c1c;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .leftSide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side Content */
        .rightSide {
            padding: 20px;
            flex: 1;
            text-align: left;
        }

        .tournament-name {
            font-size: 30px;
            font-weight: bold;
            color: red;
            margin-bottom: 10px;
        }

        .info-group {
            font-size: 20px;
            margin-bottom: 8px;
        }

        /* Button Styles */
        .btn {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            font-size: 20px;
            color: white;
            background: #ff0000;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #aa0303;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Registered Tournament for <?php echo ucfirst($category); ?></h2>
        
        <?php if ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class='leftSide'>
                    <img src="assets/<?php echo htmlspecialchars($row['thumbnail']); ?>" 
                        alt="Tournament Thumbnail" 
                        class="card-image">
                </div>
                <div class='rightSide'>
                    <div class="tournament-name">
                        <?php echo htmlspecialchars($row['tournament_name']); ?>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">Date: <?php echo htmlspecialchars($row['date']); ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">Time: <?php echo htmlspecialchars($row['time']); ?></div>
                    </div>
                    
                    <div class="button-group">
                        <a href="viewDetails.php" class="btn btn-details">View Details</a>
                        <?php if ($category == 'freefire'): ?>
                            <a href="userviewRoomCard.php" class="btn btn-room">Get Room</a>
                        <?php else: ?>
                            <a href="pubggetRoom.php" class="btn btn-room">Get Room</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-matches">
                No registered tournaments found for <?php echo ucfirst($category); ?>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
