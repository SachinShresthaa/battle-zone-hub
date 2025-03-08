<?php
// Include necessary files and start session
ob_start();

include_once 'connection.php'; // Database connection
include "headerWithProfile.php"; // Header with user profile

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}
// Initialize variables
$user_id = $_SESSION['user_id']; // Logged-in user ID
$tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$game_type = isset($_POST['game_type']) ? $_POST['game_type'] : ''; // Game type from form
$successMessage = '';
$errorMessage = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $teamName = $_POST['teamName'];
    $member1Name = $_POST['member1_name'];
    $member1UID = $_POST['member1_uid'];
    $member2Name = $_POST['member2_name'];
    $member2UID = $_POST['member2_uid'];
    $member3Name = $_POST['member3_name'];
    $member3UID = $_POST['member3_uid'];
    $member4Name = $_POST['member4_name'];
    $member4UID = $_POST['member4_uid'];
    $email = isset($_POST['email']) ? $_POST['email'] : null; // Optional for Free Fire

    // Handle team logo upload
    $teamLogo = '';
    if (isset($_FILES['teamLogo']) && $_FILES['teamLogo']['error'] == 0) {
        $uploadDir = 'uploads/';
        $teamLogo = $uploadDir . basename($_FILES['teamLogo']['name']);
        if (move_uploaded_file($_FILES['teamLogo']['tmp_name'], $teamLogo)) {
            $teamLogo = $teamLogo; // Valid upload
        } else {
            $teamLogo = ''; // Failed upload
            $errorMessage = "Failed to upload team logo.";
        }
    }

    // Insert into team_registration table
    $stmt = $conn->prepare(
        "INSERT INTO team_registration 
        (game_type, team_name, team_logo, tournament_id, 
        member1_name, member1_uid, member2_name, member2_uid, 
        member3_name, member3_uid, member4_name, member4_uid, email) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssisissssssi",
        $game_type, $teamName, $teamLogo, $tournament_id,
        $member1Name, $member1UID, $member2Name, $member2UID,
        $member3Name, $member3UID, $member4Name, $member4UID, $email
    );

    // Execute query and check for success
    if ($stmt->execute()) {
        $successMessage = "Team registered successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Registration</title>
</head>
<body>
    <!-- Registration Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="game_type">Game Type</label>
        <select id="game_type" name="game_type" required>
            <option value="Free Fire">Free Fire</option>
            <option value="PUBG">PUBG</option>
        </select>

        <label for="team-name">Team Name</label>
        <input type="text" id="team-name" name="teamName" required>

        <label for="team-logo">Team Logo</label>
        <input type="file" id="team-logo" name="teamLogo" accept="image/*">

        <label>Team Members</label>
        <input type="text" name="member1_name" placeholder="Player 1 Name" required>
        <input type="text" name="member1_uid" placeholder="Player 1 UID" required>
        <input type="text" name="member2_name" placeholder="Player 2 Name" required>
        <input type="text" name="member2_uid" placeholder="Player 2 UID" required>
        <input type="text" name="member3_name" placeholder="Player 3 Name" required>
        <input type="text" name="member3_uid" placeholder="Player 3 UID" required>
        <input type="text" name="member4_name" placeholder="Player 4 Name" required>
        <input type="text" name="member4_uid" placeholder="Player 4 UID" required>

        <label for="email">Email (for PUBG)</label>
        <input type="email" id="email" name="email">

        <button type="submit">Register</button>
    </form>

    <!-- Success or Error Messages -->
    <?php if (!empty($successMessage)): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
</body>
</html>