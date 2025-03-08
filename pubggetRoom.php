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
            font-family: 'Poppins', 'Segoe UI', sans-serif;
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

        .rooms-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .rooms-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .card-header h1 {
            color: var(--text-light);
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .card-header .game-icon {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .game-name {
            color: var(--primary-color);
            font-weight: 600;
        }

        .rooms-table-container {
            padding: 25px 30px;
            overflow-x: auto;
        }

        .rooms-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .rooms-table th {
            background-color: var(--table-header);
            color: var(--primary-color);
            font-weight: 600;
            text-align: left;
            padding: 15px 20px;
            border-bottom: 2px solid var(--primary-color);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rooms-table th:first-child {
            width: 170px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .rooms-table td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--table-border);
            position: relative;
        }

        .rooms-table tr:nth-child(odd) td {
            background-color: var(--table-row-odd);
        }

        .rooms-table tr:nth-child(even) td {
            background-color: var(--table-row-even);
        }

        .rooms-table tr:last-child td {
            border-bottom: none;
        }

        .rooms-table th:first-child, .rooms-table td:first-child {
            border-right: 1px solid var(--table-border);
        }

        .credential-value {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 8px 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .description-box {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text-faded);
            border-left: 3px solid var(--primary-color);
            margin-bottom: 5px;
        }

        .copy-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 1rem;
            padding: 5px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            color: var(--text-light);
            transform: scale(1.1);
        }

        .card-footer {
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .message {
            text-align: center;
            padding: 50px 30px;
            font-size: 1.2rem;
            color: var(--text-faded);
        }

        .message i {
            display: block;
            font-size: 3rem;
            color: var(--primary-color);
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
                    <i class="fas fa-<?php echo $gameIcon; ?> game-icon"></i>
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
