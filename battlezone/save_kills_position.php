<?php
include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teamName = $_POST['team_name'];
    $newKills = intval($_POST['kills']);
    $newPosition = intval($_POST['position']);

    // Fetch existing values from the database
    $stmt = $conn->prepare("SELECT kills, position FROM ff_team_registration WHERE team_name = ?");
    $stmt->bind_param("s", $teamName);
    $stmt->execute();
    $stmt->bind_result($prevKills, $prevPosition);
    $stmt->fetch();
    $stmt->close();

    // Add new values to existing ones
    $totalKills = $prevKills + $newKills;
    $totalPosition = $prevPosition + $newPosition; // You might need a different logic for position handling

    // Calculate points using the new total values
    $points = ($totalKills * 2) + ((20 - $totalPosition) * 5);

    // Update database with new totals
    $stmt = $conn->prepare("UPDATE ff_team_registration SET kills = ?, position = ?, points = ? WHERE team_name = ?");
    $stmt->bind_param("iiis", $totalKills, $totalPosition, $points, $teamName);
    $stmt->execute();
    $stmt->close();

    // Return updated data
    echo json_encode(["kills" => $totalKills, "position" => $totalPosition, "points" => $points]);
}

$conn->close();
?>
