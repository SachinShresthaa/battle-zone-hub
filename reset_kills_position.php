<?php
include_once 'connection.php'; // Database connection

if (isset($_POST['team_name'])) {
    $team_name = $_POST['team_name'];

    // Prepare SQL query to reset kills, position, and points for the team
    $stmt = $conn->prepare("
        UPDATE ff_team_registration
        SET kills = 0, position = 0, points = 0
        WHERE team_name = ?
    ");
    $stmt->bind_param("s", $team_name);

    if ($stmt->execute()) {
        // Prepare response with the reset values
        $response = array(
            'kills' => 0,
            'position' => 0,
            'points' => 0
        );
        echo json_encode($response); // Return the updated data in JSON format
    } else {
        // Handle error if the query fails
        echo json_encode(array('error' => 'Failed to reset data'));
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'No team name provided'));
}

$conn->close();
?>
