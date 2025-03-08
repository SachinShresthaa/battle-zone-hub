<?php
session_start();
ob_start(); // Start output buffering

include "headerWithProfile.php";
include_once 'connection.php';

$successMessage = ''; 
$errorMessage   = ''; 
$tournament_id  = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$tournament_date = "";
$tournament_name = "";
$entryFee = "200"; // Default entry fee

// Fetch tournament details (name, date, and entry fee)
if ($tournament_id > 0) {
    $stmt = $conn->prepare("SELECT name, date, price FROM tournaments WHERE id = ?");
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $stmt->bind_result($tournament_name, $tournament_date, $entryFee);
    $stmt->fetch();
    $stmt->close();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $errorMessage = "You must be logged in to register.";
} else {
    $user_email = $_SESSION['user_email'] ?? '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errorMessage)) {
    // Get team name from the form
    $teamName = $_POST['teamName'];

    // Check if the team is already registered for the tournament
    $stmt = $conn->prepare("SELECT COUNT(*) FROM pubg_team_registration WHERE team_name = ? AND tournament_id = ?");
    $stmt->bind_param("si", $teamName, $tournament_id);
    $stmt->execute();
    $stmt->bind_result($teamCount);
    $stmt->fetch();
    $stmt->close();

    // If the team is already registered, show an error message
    if ($teamCount > 0) {
        $errorMessage = "Your team is already registered for this tournament.";
    } else {
        // Store all required details in the session
        $_SESSION['registration_data'] = [
            'teamName'        => $teamName,
            'tournament_id'   => $tournament_id,
            'tournament_name' => $tournament_name,
            'member1Name'     => $_POST['member1'],
            'member1UID'      => $_POST['member1_uid'],
            'member2Name'     => $_POST['member2'],
            'member2UID'      => $_POST['member2_uid'],
            'member3Name'     => $_POST['member3'],
            'member3UID'      => $_POST['member3_uid'],
            'member4Name'     => $_POST['member4'],
            'member4UID'      => $_POST['member4_uid'],
            'user_id'         => $_SESSION['user_id'],
            'user_email'      => $_SESSION['user_email'],
        ];

        // Store team name and tournament name in session for later use
        $_SESSION['team_name'] = $teamName;
        $_SESSION['tournament_name'] = $tournament_name;

        // Debugging session values
        var_dump($_SESSION['team_name']);
        var_dump($_SESSION['tournament_name']);

        // Redirect to Khalti payment page
        header("Location: khalti2/checkout.php?tournament_id=$tournament_id&entry_fee=" . urlencode($entryFee));
        exit();
    }
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Registration Free Fire</title>
    <link rel="stylesheet" href="./CSS/teamregister.css">
</head>
<body>
    <div class="main">
        <div class="left-side">
            <img src="./ASSETS/PUBGregister.png" alt="Free Fire Registration">
        </div>
        <div class="right-side">
            <h1>Team Registration Form</h1>

            <?php if (!empty($errorMessage)) { ?>
                <script>
                    alert("<?php echo $errorMessage; ?>");
                </script>
            <?php } ?>

            <form action="teamregisterPubg.php?tournament_id=<?php echo htmlspecialchars($tournament_id); ?>" method="POST">
                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="members">Team Members (Game's Username with UID)</label>
                <div class="team-members">
                    <div class="together">
                        <input type="text" name="member1" placeholder="1. Username" required>
                        <input type="text" name="member1_uid" placeholder="1. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" name="member2" placeholder="2. Username" required>
                        <input type="text" name="member2_uid" placeholder="2. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" name="member3" placeholder="3. Username" required>
                        <input type="text" name="member3_uid" placeholder="3. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" name="member4" placeholder="4. Username" required>
                        <input type="text" name="member4_uid" placeholder="4. UID" required>
                    </div>
                </div>

                <p class="note">
                    Ensure all fields are filled correctly before submitting.<br>
                    Entry fee: Rs <?php echo htmlspecialchars($entryFee); ?>
                </p>
                <div class="change">
                    <button type="submit">Proceed to Payment</button>
                </div>
            </form>
        </div>
    </div>

    <?php include "Footer.php"; ?>
</body>
</html>