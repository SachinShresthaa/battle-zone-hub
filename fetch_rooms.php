<?php
include "connection.php";

if (!isset($_GET['tournament_id'])) {
    echo "Invalid request.";
    exit;
}

$tournament_id = intval($_GET['tournament_id']);

// Fetch the category of the tournament
$sql = "SELECT category FROM tournaments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$stmt->bind_result($category);
$stmt->fetch();
$stmt->close();

if (!$category) {
    echo "Tournament not found.";
    exit;
}

// Fetch room details for the selected tournament category
$sql = "SELECT room_id, room_password, description FROM room_details WHERE category = ? ORDER BY room_id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$stmt->bind_result($room_id, $room_password, $description);

$rooms = [];
while ($stmt->fetch()) {
    $rooms[] = ['room_id' => $room_id, 'room_password' => $room_password, 'description' => $description];
}
$stmt->close();

if (empty($rooms)) {
    echo "<p>No rooms are available for " . htmlspecialchars($category) . ".</p>";
} else {
    echo "<h2>Rooms for " . htmlspecialchars($category) . "</h2>";
    echo "<table border='1' cellspacing='0' cellpadding='10' style='color:white; margin: auto;'>";
    echo "<tr><th>Room ID</th><th>Password</th><th>Description</th></tr>";
    foreach ($rooms as $room) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($room['room_id']) . "</td>";
        echo "<td>" . htmlspecialchars($room['room_password']) . "</td>";
        echo "<td>" . nl2br(htmlspecialchars($room['description'])) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
