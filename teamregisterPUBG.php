<?php
include "headerWithProfile.php";
include_once 'connection.php'; // Database connection

$successMessage = ''; // Store success message
$errorMessage   = ''; // Store error message
$tournament_id  = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$tournament_date = "";
$entryFee = "200"; // Default entry fee

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $errorMessage = "You must be logged in to register.";
} else {
    // Set user_email to prevent undefined key warning
    $user_email = $_SESSION['user_email'] ?? '';
}

// Fetch tournament details if ID is valid
if ($tournament_id > 0) {
    $stmt = $conn->prepare("SELECT date, price FROM tournaments WHERE id = ?");
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $stmt->bind_result($tournament_date, $entryFee);
    $stmt->fetch();
    $stmt->close();
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errorMessage)) {
    // Get the form data
    $teamName    = trim($_POST['teamName']);
    $member1Name = trim($_POST['member1']);
    $member1UID  = trim($_POST['member1_uid']);
    $member2Name = trim($_POST['member2']);
    $member2UID  = trim($_POST['member2_uid']);
    $member3Name = trim($_POST['member3']);
    $member3UID  = trim($_POST['member3_uid']);
    $member4Name = trim($_POST['member4']);
    $member4UID  = trim($_POST['member4_uid']);
    $user_id     = $_SESSION['user_id'];

    // Validate UID using regex (only numbers, 6 to 12 digits)
    $uidPattern = "/^\d{6,12}$/";
    if (
        !preg_match($uidPattern, $member1UID) ||
        !preg_match($uidPattern, $member2UID) ||
        !preg_match($uidPattern, $member3UID) ||
        !preg_match($uidPattern, $member4UID)
    ) {
        $errorMessage = "Each UID must be a number between 6 to 12 digits.";
    }

    // Check for duplicate team name and usernames in the same tournament
    if (empty($errorMessage)) {
        // Check if the team name already exists in the tournament
        $stmt = $conn->prepare("SELECT id FROM ff_team_registration WHERE tournament_id = ? AND team_name = ?");
        $stmt->bind_param("is", $tournament_id, $teamName);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errorMessage = "The team name already exists for this tournament.";
        }
        $stmt->close();

        // Check if any of the usernames already exist in the tournament
        if (empty($errorMessage)) {
            $stmt = $conn->prepare("SELECT id FROM ff_team_registration WHERE tournament_id = ? 
                                    AND (member1_uid = ? OR member2_uid = ? OR member3_uid = ? OR member4_uid = ?)");
            $stmt->bind_param("issss", $tournament_id, $member1UID, $member2UID, $member3UID, $member4UID);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errorMessage = "One or more usernames are already registered for this tournament.";
            }
            $stmt->close();
        }
    }

    // Insert team into the database if no errors
    if (empty($errorMessage)) {
        $stmt = $conn->prepare(
            "INSERT INTO pubg_team_registration
            (team_name, tournament_id, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid, user_id, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sississsssis",
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

        if ($stmt->execute()) {
            $successMessage = "";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
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
            <img src="./ASSETS/PUBGregister.png" alt="Free Fire Registration">
        </div>
        <div class="right-side">
            <h1>Team Registration Form</h1>
            <form id="registrationForm" action="teamregisterPUBG.php?tournament_id=<?php echo htmlspecialchars($tournament_id); ?>" method="POST">
                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="members">Team Members (Game's Username with UID)</label>
                <div class="team-members">
                    <div class="together">
                        <input type="text" id="member1" name="member1" placeholder="1. Username" required>
                        <input type="text" id="member1_uid" name="member1_uid" placeholder="1. UID (6-12 digits)" required>
                    </div>
                    <div class="together">
                        <input type="text" id="member2" name="member2" placeholder="2. Username" required>
                        <input type="text" id="member2_uid" name="member2_uid" placeholder="2. UID (6-12 digits)" required>
                    </div>
                    <div class="together">
                        <input type="text" id="member3" name="member3" placeholder="3. Username" required>
                        <input type="text" id="member3_uid" name="member3_uid" placeholder="3. UID (6-12 digits)" required>
                    </div>   
                    <div class="together"> 
                        <input type="text" id="member4" name="member4" placeholder="4. Username" required>
                        <input type="text" id="member4_uid" name="member4_uid" placeholder="4. UID (6-12 digits)" required>
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

    <!-- Success Message Popup and Redirect -->
    <?php if (!empty($successMessage)) { ?>
        <script>
            alert("<?php echo $successMessage; ?>");
            window.location.href = "payment.php?tournament_id=<?php echo $tournament_id; ?>&entry_fee=<?php echo urlencode($entryFee); ?>";
        </script>
    <?php } ?>

    <!-- Error Message Popup -->
    <?php if (!empty($errorMessage)) { ?>
        <script>
            alert("<?php echo $errorMessage; ?>");
        </script>
    <?php } ?>

    <!-- Prevent Default and Additional Client-side Validation -->
    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            var teamName = document.getElementById("team-name").value;
            var member1UID = document.getElementById("member1_uid").value;
            var member2UID = document.getElementById("member2_uid").value;
            var member3UID = document.getElementById("member3_uid").value;
            var member4UID = document.getElementById("member4_uid").value;

            // Add your custom validation logic here (if needed)
            // For example, check if all UID fields are filled properly before submitting

            if (!teamName || !member1UID || !member2UID || !member3UID || !member4UID) {
                alert("Please fill in all fields.");
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>
