<?php
session_start();
include_once '../connection.php'; // Database connection

// Get the team name and user email from the GET request
$team_name = $_GET['team_name'] ?? '';
$user_email = $_GET['user_email'] ?? '';

if (!empty($team_name) && !empty($user_email)) {
    // Prepare the SQL query to delete the team
    $stmt = $conn->prepare("DELETE FROM ff_team_registration WHERE team_name = ? AND user_email = ?");
    $stmt->bind_param("ss", $team_name, $user_email);

    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(['success' => true, 'message' => 'Team deleted successfully.']);
    } else {
        // Return an error response
        echo json_encode(['success' => false, 'message' => 'Error deleting team: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    // Return an error response if parameters are missing
    echo json_encode(['success' => false, 'message' => 'Invalid team name or user email.']);
}

$conn->close();
?>