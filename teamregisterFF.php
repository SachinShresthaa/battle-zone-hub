<?php
include "headerWithProfile.php";
include_once 'connection.php'; // Database connection

$successMessage = ''; // Variable to store success message
$errorMessage   = ''; // Variable to store error message
$tournament_id  = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$tournament_date = "";
$entryFee = "200"; // Default value in case no value is fetched

// Fetch tournament details based on the ID (fetching date and entry fee)
if ($tournament_id > 0) {
    $stmt = $conn->prepare("SELECT date, price FROM tournaments WHERE id = ?");
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $stmt->bind_result($tournament_date, $entryFee);
    $stmt->fetch();
    $stmt->close();
}

// Ensure user is logged in before registering
if (!isset($_SESSION['user_id'])) {
    $errorMessage = "You must be logged in to register.";
} else {
    // Set user_email to prevent undefined key warning
    $user_email = $_SESSION['user_email'] ?? '';
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errorMessage)) {
    // Get the form data
    $teamName    = $_POST['teamName'];
    $member1Name = $_POST['member1'];
    $member1UID  = $_POST['member1_uid'];
    $member2Name = $_POST['member2'];
    $member2UID  = $_POST['member2_uid'];
    $member3Name = $_POST['member3'];
    $member3UID  = $_POST['member3_uid'];
    $member4Name = $_POST['member4'];
    $member4UID  = $_POST['member4_uid'];

    // Fetch the logged-in user ID and email from the session
    $user_id = $_SESSION['user_id'];
    $user_email = $_SESSION['user_email']; // Make sure this is set during login

    // Check if the user is already registered for the same tournament
    $stmt = $conn->prepare("SELECT COUNT(*) FROM ff_team_registration WHERE user_id = ? AND tournament_id = ?");
    $stmt->bind_param("ii", $user_id, $tournament_id);
    $stmt->execute();
    $stmt->bind_result($existingRegistrationCount);
    $stmt->fetch();
    $stmt->close();

    // If the user is already registered, show an error message
    if ($existingRegistrationCount > 0) {
        $errorMessage = "You have already registered for this tournament.";
    } else {
        // If not registered, proceed with the registration
        if ($tournament_id > 0) {
            // Prepare the SQL query for inserting the registration
            $stmt = $conn->prepare(
                "INSERT INTO ff_team_registration 
                (team_name, tournament_id, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid, user_id, user_email) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            // Bind the parameters
            $stmt->bind_param(
                "sissssssssis", // Correct data types for all parameters
                $teamName,
                $tournament_id,
                $member1Name,
                $member1UID,
                $member2Name,
                $member2UID,
                $member3Name,
                $member3UID,
                $member4Name,
                $member4UID,
                $user_id,
                $user_email
            );

            // Execute the query
            if ($stmt->execute()) {
                $successMessage = "Team registered successfully!";
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
        } else {
            $errorMessage = "Invalid tournament ID.";
        }
    }
}
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
            <img src="./ASSETS/FFregister.png" alt="Free Fire Registration" style="margin-top: 0px;">
        </div>
        <div class="right-side">
            <h1>Team Registration Form</h1>
            <form action="teamregisterff.php?tournament_id=<?php echo htmlspecialchars($tournament_id); ?>" method="POST">
                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="members">Team Members (Game's Username with UID)</label>
                <div class="team-members">
                    <div class="together">
                        <input type="text" id="member1" name="member1" placeholder="1. Username" required>
                        <input type="text" id="member1_uid" name="member1_uid" placeholder="1. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" id="member2" name="member2" placeholder="2. Username" required>
                        <input type="text" id="member2_uid" name="member2_uid" placeholder="2. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" id="member3" name="member3" placeholder="3. Username" required>
                        <input type="text" id="member3_uid" name="member3_uid" placeholder="3. UID" required>
                    </div>
                    <div class="together">
                        <input type="text" id="member4" name="member4" placeholder="4. Username" required>
                        <input type="text" id="member4_uid" name="member4_uid" placeholder="4. UID" required>
                    </div>
                </div>

                <p class="note">
                    Ensure all fields are filled correctly before submitting.<br>
                    Entry fee: Rs <?php echo htmlspecialchars($entryFee); ?>
                </p>
                <div class="change">
                    <button type="submit">Register Now</button>
                </div>
            </form>
        </div>
    </div>

    <?php include "Footer.php"; ?>

    <!-- Success Message Popup and Redirect to Khalti Checkout -->
    <?php if (!empty($successMessage)) { ?>
        <script type="text/javascript">
            window.location.href = "khalti/checkout.php?tournament_id=<?php echo $tournament_id; ?>&entry_fee=<?php echo urlencode($entryFee); ?>";
        </script>
    <?php } ?>

    <!-- Error Message Popup -->
    <?php if (!empty($errorMessage)) { ?>
        <script type="text/javascript">
            alert("<?php echo $errorMessage; ?>");
        </script>
    <?php } ?>
</body>
</html>
