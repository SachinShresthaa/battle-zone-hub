<?php
include 'connection.php';

// Start session if not already started
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get category parameter from the URL for redirection
$category = isset($_GET['category']) ? $_GET['category'] : 'freefire';

// Handle match cancellation
if (isset($_GET['cancel_match']) && isset($_GET['tournament_id'])) {
    $tournament_id = $_GET['tournament_id'];

    // Check if the user has the match registered for cancellation
    $check_query = "SELECT * FROM ff_team_registration WHERE user_id = ? AND tournament_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $user_id, $tournament_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Delete the match registration from the database
        $delete_query = "DELETE FROM ff_team_registration WHERE user_id = ? AND tournament_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $user_id, $tournament_id);

        if ($delete_stmt->execute()) {
            $_SESSION['message'] = "Match registration canceled successfully.";
        } else {
            $_SESSION['error'] = "Error canceling match registration. Please try again.";
        }
    } else {
        $_SESSION['error'] = "No match found to cancel.";
    }
}

// Redirect back to my_matches.php with the selected category
header("Location: myMatches.php?category=" . urlencode($category));
exit();