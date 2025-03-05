<?php
include_once '../connection.php'; // Ensure correct database connection

header('Content-Type: application/json'); // Ensure JSON response format

if (isset($_GET['team_name']) && isset($_GET['user_email'])) {
    include_once '../connection.php'; // Include database connection

    $team_name = trim($_GET['team_name']);
    $user_email = trim($_GET['user_email']);

    // Enable MySQLi error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // Prepare delete query
        $stmt = $conn->prepare("DELETE FROM pubg_team_registration WHERE team_name = ? AND email = ?");
        
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "SQL Prepare Error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("ss", $team_name, $user_email);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Team '$team_name' deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete team: " . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Exception: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request parameters"]);
}
?>
