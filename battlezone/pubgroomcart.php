<?php
session_start();
include "connection.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's registered tournament category (PUBG or FreeFire)
$sql = "SELECT t.category FROM pubg_team_registration r
        JOIN tournaments t ON r.tournament_id = t.id
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($game_choice);
$stmt->fetch();
$stmt->close();

if (!$game_choice) {
    echo "You are not registered for any tournament.";
    exit;
}

// Fetch room details for the registered tournament (PUBG or FreeFire)
$sql = "SELECT room_id, room_password, description FROM room_details WHERE category = ? ORDER BY room_id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $game_choice);
$stmt->execute();
$stmt->bind_result($room_id, $room_password, $description);

$rooms = [];
while ($stmt->fetch()) {
    $rooms[] = ['room_id' => $room_id, 'room_password' => $room_password, 'description' => $description];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { width: 80%; margin: 50px auto; padding: 20px; background-color: white; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        h1 { text-align: center; font-size: 32px; color: #333; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 15px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        td { background-color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Rooms for <?php echo htmlspecialchars($game_choice); ?></h1>
        <?php if (empty($rooms)) { ?>
            <p class="message">No rooms are currently available for <?php echo htmlspecialchars($game_choice); ?>.</p>
        <?php } else { ?>
            <table>
                <tr><th>Room ID</th><?php foreach ($rooms as $room) { echo "<td>" . htmlspecialchars($room['room_id']) . "</td>"; } ?></tr>
                <tr><th>Password</th><?php foreach ($rooms as $room) { echo "<td>" . htmlspecialchars($room['room_password']) . "</td>"; } ?></tr>
                <tr><th>Description</th><?php foreach ($rooms as $room) { echo "<td>" . htmlspecialchars($room['description']) . "</td>"; } ?></tr>
            </table>
        <?php } ?>
    </div>
</body>
</html>
