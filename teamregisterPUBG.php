<?php
include "Header.php";
include_once 'connection.php'; // Database connection

// Fetch the tournament ID from the URL
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
    // Get form data
    $teamName = $_POST['teamName'];
    $member1Name = $_POST['member1_name'];
    $member1UID = $_POST['member1_uid'];
    $member2Name = $_POST['member2_name'];
    $member2UID = $_POST['member2_uid'];
    $member3Name = $_POST['member3_name'];
    $member3UID = $_POST['member3_uid'];
    $member4Name = $_POST['member4_name'];
    $member4UID = $_POST['member4_uid'];
    $tournamentId = $_POST['tournament_id'];

    // Handle file upload for team logo
    $teamLogo = '';
    if (isset($_FILES['teamLogo']) && $_FILES['teamLogo']['error'] == 0) {
        $uploadDir = 'uploads/';
        $teamLogo = $uploadDir . basename($_FILES['teamLogo']['name']);
        move_uploaded_file($_FILES['teamLogo']['tmp_name'], $teamLogo);
    }

    // Insert registration data into the database
    $stmt = $conn->prepare("INSERT INTO pubg_team_registration (team_name, team_logo, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid, tournament_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssi", $teamName, $teamLogo, $member1Name, $member1UID, $member2Name, $member2UID, $member3Name, $member3UID, $member4Name, $member4UID, $tournamentId);

    if ($stmt->execute()) {
        echo "<p>Team registered successfully for the tournament!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Registration PUBG</title>
    <link rel="stylesheet" href="./CSS/teamregister.css">
</head>
<body>
    <div class="main">
        <div class="left-side">
            <img src="./ASSETS/pubgRegister.png" alt="PUBG Registration" style="margin-top: 0px;">
        </div>
        <div class="right-side">
            <h1>Team Registration Form</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="tournament-date">Tournament Date</label>
                <input type="text" id="tournament-date" name="tournament_date" value="<?php echo htmlspecialchars($tournament_date); ?>" readonly>

                <input type="hidden" name="tournament_id" value="<?php echo htmlspecialchars($tournament_id); ?>">

                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="team-logo">Your Team Logo</label>
                <input type="file" id="team-logo" name="teamLogo" accept="image/*">

                <label>Team Members (Game's Username and UID)</label>
                <div class="team-members">
                    <div class="member">
                        <label for="member1-name">Player 1 Name</label>
                        <input type="text" id="member1-name" name="member1_name" placeholder="Player 1 Name" required>
                        <label for="member1-uid">Player 1 UID</label>
                        <input type="text" id="member1-uid" name="member1_uid" placeholder="Player 1 UID" required>
                    </div>
                    <div class="member">
                        <label for="member2-name">Player 2 Name</label>
                        <input type="text" id="member2-name" name="member2_name" placeholder="Player 2 Name" required>
                        <label for="member2-uid">Player 2 UID</label>
                        <input type="text" id="member2-uid" name="member2_uid" placeholder="Player 2 UID" required>
                    </div>
                    <div class="member">
                        <label for="member3-name">Player 3 Name</label>
                        <input type="text" id="member3-name" name="member3_name" placeholder="Player 3 Name" required>
                        <label for="member3-uid">Player 3 UID</label>
                        <input type="text" id="member3-uid" name="member3_uid" placeholder="Player 3 UID" required>
                    </div>
                    <div class="member">
                        <label for="member4-name">Player 4 Name</label>
                        <input type="text" id="member4-name" name="member4_name" placeholder="Player 4 Name" required>
                        <label for="member4-uid">Player 4 UID</label>
                        <input type="text" id="member4-uid" name="member4_uid" placeholder="Player 4 UID" required>
                    </div>
                </div>
                <p class="note">Ensure all fields are filled correctly before submitting.<br> Entry fee: NPR200</p>
                <button type="submit">Register Now</button>
            </form>
        </div>
    </div>
    <?php include "Footer.php"; ?>
</body>
</html>
