<?php
include_once '../../connection.php'; // Database connection
session_start(); // Start session

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the team name and user email from the form submission
    $team_name = isset($_POST['team_name']) ? trim($_POST['team_name']) : null;
    $user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : null;

    // Validate that both values are provided
    if (empty($team_name) || empty($user_email)) {
        echo "<script>alert('Invalid request. Please try again.'); window.location.href='view_teams.php';</script>";
        exit;
    }

    // Delete the entire team record from the database
    $stmt = $conn->prepare("DELETE FROM ff_team_registration WHERE team_name = ? AND user_email = ?");
    $stmt->bind_param("ss", $team_name, $user_email);
    $stmt->execute();

    // Check if deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Team \"$team_name\" deleted successfully!'); window.location.href='../index.php?tournaments';</script>";
    } else {
        echo "<script>alert('Error: Team not found or already deleted.'); window.location.href='../index.php?tournaments';</script>";
    }

    $stmt->close();
} else {
    // Redirect if accessed directly
    echo "<script>alert('Invalid access!'); window.location.href='index.php';</script>";
}

// Close the database connection
$conn->close();
?>
