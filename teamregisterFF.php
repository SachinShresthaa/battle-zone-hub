<?php
include "Header.php";
include_once 'connection.php'; // Database connection

$successMessage = ''; // Variable to store success message
$errorMessage = ''; // Variable to store error message
$tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$tournament_date = "";




// Fetch tournament details based on the ID
if ($tournament_id > 0) {
    $stmt = $conn->prepare("SELECT date FROM tournaments WHERE id = ?");
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $stmt->bind_result($tournament_date);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $teamName = $_POST['teamName'];
    $member1Name = $_POST['member1'];
    $member1UID = $_POST['member1_uid'];
    $member2Name = $_POST['member2'];
    $member2UID = $_POST['member2_uid'];
    $member3Name = $_POST['member3'];
    $member3UID = $_POST['member3_uid'];
    $member4Name = $_POST['member4'];
    $member4UID = $_POST['member4_uid'];

    // Handling file upload for team logo
    $teamLogo = '';
    if (isset($_FILES['teamLogo']) && $_FILES['teamLogo']['error'] == 0) {
        $uploadDir = 'uploads/'; // Directory to save the image
        $teamLogo = $uploadDir . basename($_FILES['teamLogo']['name']);
        move_uploaded_file($_FILES['teamLogo']['tmp_name'], $teamLogo);
    }

    // Check if the tournament ID is valid
    if ($tournament_id > 0) {
        // Prepare the SQL query for inserting the registration
        $stmt = $conn->prepare(
            "INSERT INTO ff_team_registration 
            (team_name, team_logo, tournament_id, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // Bind the parameters
        $stmt->bind_param(
            "ssississsss",
            $teamName,
            $teamLogo,
            $tournament_id,
            $member1Name,
            $member1UID,
            $member2Name,
            $member2UID,
            $member3Name,
            $member3UID,
            $member4Name,
            $member4UID
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
            <form action="teamregisterff.php?tournament_id=<?php echo htmlspecialchars($tournament_id); ?>" method="POST" enctype="multipart/form-data">
                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="team-logo">Your Team Logo</label>
                <input type="file" id="team-logo" name="teamLogo" accept="image/*">

                <label for="members">Team Members (Game's Username with UID)</label>
                <div class="team-members">
                    <input type="text" id="member1" name="member1" placeholder="1. First Player (with UID)" required>
                    <input type="text" id="member1_uid" name="member1_uid" placeholder="UID" required>
                    <input type="text" id="member2" name="member2" placeholder="2. Second Player (with UID)" required>
                    <input type="text" id="member2_uid" name="member2_uid" placeholder="UID" required>
                    <input type="text" id="member3" name="member3" placeholder="3. Third Player (with UID)" required>
                    <input type="text" id="member3_uid" name="member3_uid" placeholder="UID" required>
                    <input type="text" id="member4" name="member4" placeholder="4. Fourth Player (with UID)" required>
                    <input type="text" id="member4_uid" name="member4_uid" placeholder="UID" required>
                </div>
                <p class="note">Ensure all fields are filled correctly before submitting.<br> Entry fee: NPR 200</p>
                <button type="submit">Register Now</button>
            </form>    
        </div>
    </div>

    <?php include "Footer.php"; ?>

    <!-- Success Message Popup -->
    <?php if (!empty($successMessage)) { ?>
        <script type="text/javascript">
            alert("<?php echo $successMessage; ?>");
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
