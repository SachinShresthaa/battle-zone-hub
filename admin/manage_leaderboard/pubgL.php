<?php

include_once '../connection.php';

// Function to calculate position points
function getPositionPoints($position) {
    if ($position == 1) return 15;
    if ($position == 2) return 12;
    if ($position == 3) return 10;
    if ($position == 4) return 8;
    if ($position == 5) return 6;
    if ($position <= 7) return 4;
    if ($position <= 12) return 2;
    if ($position <= 16) return 1;
    return 0;
}

// Fetch the latest tournament's ID for PUBG
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM pubg_team_registration");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$latest_tournament_id = $row['latest_tournament'];
$stmt->close();

// Handle update request for kills and position
$message = ""; // Variable to store success or error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $team_name = $_POST['update'];
        $new_kills = isset($_POST['kills']) ? min(255, max(0, intval($_POST['kills']))) : 0;
        $position = isset($_POST['position']) ? intval($_POST['position']) : 0;
        
        // Get current kills & points for PUBG
        $stmt = $conn->prepare("SELECT kills, points FROM pubg_team_registration WHERE team_name = ? AND tournament_id = ? LIMIT 1");
        $stmt->bind_param("si", $team_name, $latest_tournament_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_data = $result->fetch_assoc();
        $current_kills = intval($current_data['kills']);
        $current_points = intval($current_data['points']);
        $stmt->close();
        
        // Calculate total kills and points
        $total_kills = min(255, $current_kills + $new_kills);
        $position_points = getPositionPoints($position); 
        $total_points = $current_points + $position_points; // Add new position points to previous total points
        
        // Update team data for PUBG
        $stmt = $conn->prepare("UPDATE pubg_team_registration 
                               SET kills = ?, 
                                   points = ?,
                                   position = ? 
                               WHERE team_name = ? AND tournament_id = ?");

        $stmt->bind_param("iiisi", $total_kills, $total_points, $position, $team_name, $latest_tournament_id);
        if ($stmt->execute()) {
            $message = "Leaderboard updated successfully!";
        } else {
            $message = "Error updating leaderboard.";
        }
        $stmt->close();

        // Update leaderboard for PUBG
        $stmt = $conn->prepare("INSERT INTO leaderboard (team_name, kills, points, position)
                               VALUES (?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE 
                               kills = ?, 
                               points = ?, 
                               position = ?");
        
        $stmt->bind_param("siiiiii", $team_name, $total_kills, $total_points, $position, 
                         $total_kills, $total_points, $position);
        if ($stmt->execute()) {
            $message = "Leaderboard updated successfully!";
        } else {
            $message = "Error updating leaderboard.";
        }
        $stmt->close();
    }

    // Check for delete action for PUBG
    if (isset($_POST['delete'])) {
        $team_name = $_POST['delete'];

        // Delete the team from the PUBG registration table
        $stmt = $conn->prepare("DELETE FROM pubg_team_registration WHERE team_name = ?");
        $stmt->bind_param("s", $team_name);
        if ($stmt->execute()) {
            $message = "Team deleted successfully!";
        } else {
            $message = "Error deleting team.";
        }
        $stmt->close();

        // Set default points for the team in leaderboard if deleted
        $stmt = $conn->prepare("INSERT INTO leaderboard (team_name, kills, points, position) 
                               VALUES (?, 0, 0, 0)
                               ON DUPLICATE KEY UPDATE kills = 0, points = 0, position = 0");
        $stmt->bind_param("s", $team_name);
        $stmt->execute();
        $stmt->close();
    }
}

// Display leaderboard for PUBG
if ($latest_tournament_id) {
    echo "<h2>PUBG Tournament Leaderboard</h2>";

    $stmt = $conn->prepare("SELECT team_name, kills, points, position
                              FROM pubg_team_registration 
                              WHERE tournament_id = ? 
                              ORDER BY points DESC, kills DESC, position ASC");
    
    $stmt->bind_param("i", $latest_tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<table border='1' cellspacing='0' cellpadding='8' style='width:70%;margin-left:200px;'>
            <tr>
                <th>Rank</th>
                <th>Team Name</th>
                <th>Total Kills</th>
                <th>Total Points</th>
                <th>Action</th>
            </tr>";

    $rank = 1;
    
    while ($team = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$rank}</td>
                <td>{$team['team_name']}</td>
                <td>{$team['kills']}</td>
                <td>{$team['points']}</td>
                <td>
                    <button onclick='openUpdateKillsModal(\"{$team['team_name']}\", {$team['kills']}, {$team['position']})' style='background-color:green;padding:5px;border-radius:5px;border-style:none;'>Update Score</button>
                    <form method='POST' style='display:inline-block;'>
                        <input type='hidden' name='delete' value='{$team['team_name']}'>
                        <button type='submit' style='background-color:red;padding:5px;border-radius:5px;border-style:none;'>Delete Team</button>
                    </form>
                </td>
              </tr>";
        $rank++;
    }
    echo "</table>";
} else {
    echo "<p>No tournaments found.</p>";
}

$conn->close();
?>

<!-- Show Message -->
<?php if (!empty($message)) : ?>
    <script>
        alert("<?php echo $message; ?>");
        window.location.href = window.location.href; // Refresh the page
    </script>
<?php endif; ?>

<!-- Update Kills and Position Modal -->
<div id="updateKillsModal" style="display:none; position:fixed; top:20%; left:40%; width:20%; height:auto; background-color: rgb(250, 250, 250); border-radius: 8px; padding: 20px;">
    <h3>Update Kills and Position <span id="teamNameForUpdate"></span></h3>
    <form method="POST" action="">
        <input type="hidden" name="update" id="teamNameForUpdateField">
        <label for="newKills">New Kills:</label>
        <input type="number" name="kills" id="newKills" min="0" max="255" required>
        <br><br>
        <label for="newPosition">Position:</label>
        <input type="number" name="position" id="newPosition" min="1" max="16" required>
        <br><br>
        <button type="submit">Update</button>
        <button type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<script>
// Function to open the modal with team details
function openUpdateKillsModal(teamName, currentKills, currentPosition) {
    document.getElementById('updateKillsModal').style.display = 'block';
    document.getElementById('teamNameForUpdate').innerText = teamName;
    document.getElementById('newKills').value = currentKills;
    document.getElementById('newPosition').value = currentPosition;
    document.getElementById('teamNameForUpdateField').value = teamName;
}

// Function to close the modal
function closeModal() {
    document.getElementById('updateKillsModal').style.display = 'none';
}
</script>
</script>
<style>
/* Upper form section */
.upper-form {
    width: 100%;
    padding: 0px 100px; /* Adjusted padding for better responsiveness */
}

/* Data fetching container */
.fetch-data {
    width: 100%;
    padding: 50px 150px; /* Adjusted padding for better look */
}

/* Title */
h2 {
    text-align: center;
    font-size: 36px;
    padding-top:30px;
    color: #E74C3C; /* Red color for emphasis */
}

/* Form group */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-size: 22px;
    padding: 7px;
    margin-top: 7px;
    margin-bottom: -12px;
    color:rgb(0, 0, 0);
}

/* Inputs and selects */
input, select {
    width: 100%;
    background-color: #2E2E2E;
    color: white;
    border-radius: 15px;
    padding: 18px;
    font-size: 20px;
}

input:focus, select:focus {
    outline: none;
}

/* Submit button */
.btn {
    display: inline-block;
    padding: 18px;
    background: rgb(229, 23, 23);
    color: white;
    text-decoration: none;
    border-radius: 15px;
    text-align: center;
    cursor: pointer;
    border: none;
    font-size: 22px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: rgb(255, 49, 49);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

table th, table td {
    padding: 15px;
    text-align: center;
    color: black;
    background-color: #d4d2d2;
    border: 2px solid white;
    font-size: 18px;
    transition: background-color 0.3s ease;
}

table th {
    background-color: #2E2E2E;
    color: white;
    font-size: 20px;
}

table td {
    font-size: 18px;
}
</style>