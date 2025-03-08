<?php
include "connection.php";

// Ensure user is logged in (you might want to check session or authentication here)
session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "You need to log in first.";
    exit;
}

// Fetch the user's registered tournament category and tournament ID
$sql = "SELECT t.category, t.id 
        FROM pubg_team_registration r
        JOIN tournaments t ON r.tournament_id = t.id
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($game_choice, $tournament_id);
$stmt->fetch();
$stmt->close();

if (!$game_choice) {
    echo "You are not registered for any tournament.";
    exit;
}

// Fetch room details for the registered tournament
$sql = "SELECT room_id, room_password, description 
        FROM room_details r
        JOIN tournaments t ON r.tournament_id = t.id
        WHERE t.id = ? 
        ORDER BY room_id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);  // Filter rooms based on tournament_id
$stmt->execute();
$stmt->bind_result($room_id, $room_password, $description);
$rooms = [];
while ($stmt->fetch()) {
    $rooms[] = ['room_id' => $room_id, 'room_password' => $room_password, 'description' => $description];
}
$stmt->close();

// Set game-specific colors based on game choice
if (strtolower($game_choice) === 'pubg') {
    $primaryColor = '#f1c40f';    // Yellow
    $secondaryColor = '#e67e22';  // Orange
    $accentColor = '#d35400';     // Dark Orange
    $gameIcon = 'gamepad';
} else {
    $primaryColor = '#3498db';    // Blue (FreeFire)
    $secondaryColor = '#2980b9';  // Dark Blue (FreeFire)
    $accentColor = '#1abc9c';     // Teal
    $gameIcon = 'fire';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - <?php echo htmlspecialchars($game_choice); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: <?php echo $primaryColor; ?>;
            --secondary-color: <?php echo $secondaryColor; ?>;
            --accent-color: <?php echo $accentColor; ?>;
            --background-dark: #121212;
            --card-bg: #1e1e1e;
            --text-light: #ffffff;
            --text-faded: #b3b3b3;
            --table-border: rgba(255, 255, 255, 0.1);
            --table-header: rgba(0, 0, 0, 0.3);
            --table-row-odd: rgba(255, 255, 255, 0.03);
            --table-row-even: rgba(255, 255, 255, 0.01);
        }

        body {
            background-color: var(--background-dark);
            color: var(--text-light);
            font-family: Jacques Francois;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: radial-gradient(circle at 10% 20%, rgba(0, 0, 0, 0.8) 0%, var(--background-dark) 90%);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            
        }
        .card-header {
            padding: 20px 30px;
            text-align: center;
        }
        .rooms-table-container {
            padding: 25px 30px;
            overflow-x: auto;
        }

        .rooms-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            
            border: 2px solid white;
        }

        .rooms-table th {
            background-color:rgb(70, 68, 68);
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 15px 20px;
            border-bottom: 2px solid white;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rooms-table th:first-child {
            width: 170px;
            text-align: center;
            background-color: rgb(70, 68, 68);
            
        }

        .rooms-table td {
            padding: 15px 20px;
            border-bottom: 1px solid white;
            position: relative;
            
            border: 2px solid white;
        }

        .rooms-table tr:nth-child(odd) td {
            background-color: rgb(221, 219, 219);
            color:black;
            font-size:22px;
            
            font-weight: bold;
        }

        .rooms-table tr:nth-child(even) td {
            background-color: rgb(221, 219, 219);
            color:black;
            font-size:22px;
            
            font-weight: bold;
        }

        .rooms-table tr:last-child td {
            border-bottom: none;
        }

        .rooms-table th:first-child, .rooms-table td:first-child {
            border-right: 1px solid var(--table-border);
        }

        .credential-value {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 8px;
            border-radius: 6px;
            font-family: Jacques Francois;
            letter-spacing: 1px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .copy-btn {
            background: none;
            border: none;
            color: black;
            cursor: pointer;
            font-size: 1rem;
            padding: 5px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            color: blue;
            transform: scale(1.1);
        }
        .message {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: white;
        }

        .message i {
            display: block;
            font-size: 3rem;
            color: white;
            margin-bottom: 20px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="rooms-card">
            <div class="card-header">
                <h1>
                    Available Rooms for <span class="game-name"><?php echo htmlspecialchars($game_choice); ?></span>
                </h1>
            </div>
            
            <div class="rooms-table-container">
                <?php if (empty($rooms)) { ?>
                    <div class="message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>No rooms are currently available for <?php echo htmlspecialchars($game_choice); ?>.</p>
                    </div>
                <?php } else { ?>
                    <table class="rooms-table">
                        <tr>
                            <th>Details</th>
                            <?php 
                            for ($i = 0; $i < count($rooms); $i++) { 
                                echo "<th>Room " . ($i + 1) . "</th>"; 
                            } 
                            ?>
                        </tr>
                        <tr>
                            <td>Room ID</td>
                            <?php foreach ($rooms as $room) { ?>
                                <td data-label="Room ID">
                                    <div class="credential-value">
                                        <?php echo htmlspecialchars($room['room_id']); ?>
                                        <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($room['room_id']); ?>')" title="Copy to clipboard">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <?php foreach ($rooms as $room) { ?>
                                <td data-label="Password">
                                    <div class="credential-value">
                                        <?php echo htmlspecialchars($room['room_password']); ?>
                                        <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($room['room_password']); ?>')" title="Copy to clipboard">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <?php foreach ($rooms as $room) { ?>
                                <td data-label="Description">
                                    <div class="description-box">
                                        <?php echo htmlspecialchars($room['description']); ?>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert("Copied to clipboard!");
            });
        }
    </script>
</body>
</html>
