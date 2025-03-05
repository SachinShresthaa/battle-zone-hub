<?php

include_once '../connection.php';
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

// Fetch the latest tournament's ID
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM ff_team_registration");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$latest_tournament_id = $row['latest_tournament'];
$stmt->close();

// Handle update request for kills and position
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $team_name = $_POST['update'];
        $new_kills = isset($_POST['kills']) ? min(255, max(0, intval($_POST['kills']))) : 0;
        $new_position = isset($_POST['position']) ? intval($_POST['position']) : 0;
        
        // Fetch current kills and points
        $stmt = $conn->prepare("SELECT kills, points FROM ff_team_registration WHERE team_name = ? AND tournament_id = ? LIMIT 1");
        $stmt->bind_param("si", $team_name, $latest_tournament_id);
        $stmt->execute(); 

        $result = $stmt->get_result();
        $current_data = $result->fetch_assoc();
        $stmt->close();
        
        $current_kills = intval($current_data['kills']);
        $current_points = intval($current_data['points']);

        // Calculate cumulative kills and position points
        $total_kills = min(255, $current_kills + $new_kills);
        $position_points = getPositionPoints($new_position);
        $total_points = $current_points + $new_kills + $position_points; // **Cumulative points addition**

        // Update team data
        $stmt = $conn->prepare("UPDATE ff_team_registration 
                               SET kills = CAST(? AS UNSIGNED), 
                                   points = CAST(? AS UNSIGNED)
                               WHERE team_name = ? AND tournament_id = ?");
        $stmt->bind_param("iisi", $total_kills, $total_points, $team_name, $latest_tournament_id);
        $stmt->execute();
        $stmt->close();

        // Update leaderboard
        $stmt = $conn->prepare("INSERT INTO leaderboard (team_name, kills, points, position)
                               VALUES (?, CAST(? AS UNSIGNED), CAST(? AS UNSIGNED), CAST(? AS UNSIGNED))
                               ON DUPLICATE KEY UPDATE 
                               kills = CAST(? AS UNSIGNED), 
                               points = CAST(? AS UNSIGNED)");
        
        $stmt->bind_param("siiii", $team_name, $total_kills, $total_points, $new_position, 
                         $total_kills, $total_points);
        $stmt->execute();
        $stmt->close();
    }

    // Check for delete action
    if (isset($_POST['delete'])) {
        $team_name = $_POST['delete'];

        // Delete the team from the database (for example, from `ff_team_registration`)
        $stmt = $conn->prepare("DELETE FROM ff_team_registration WHERE team_name = ?");
        $stmt->bind_param("s", $team_name);
        $stmt->execute();
        $stmt->close();

        // Remove team from leaderboard
        $stmt = $conn->prepare("DELETE FROM leaderboard WHERE team_name = ?");
        $stmt->bind_param("s", $team_name);
        $stmt->execute();
        $stmt->close();
    }
}

// Display leaderboard
if ($latest_tournament_id) {
    echo "<h2>Free Fire Leaderboard</h2>";

    $stmt = $conn->prepare("SELECT team_name, kills, points 
                              FROM ff_team_registration 
                              WHERE tournament_id = ? 
                              ORDER BY points DESC, kills DESC");
    
    $stmt->bind_param("i", $latest_tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<table border='1' cellspacing='0' cellpadding='8'style='width:70%;margin-left:200px;'>
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
                    <button style='background-color:green;padding:5px;border-radius:5px;' onclick='openUpdateKillsModal(\"{$team['team_name']}\", {$team['kills']})'>Update Score</button>
                    <form method='POST' style='display:inline-block;'>
                        <input type='hidden' name='delete' value='{$team['team_name']}'>
                        <button type='submit' style='background-color:red;padding:5px;border-radius:5px;'>Delete Team</button>
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

<!-- Update Kills and Position Modal -->
<div id="updateKillsModal" style="display:none; position:fixed; top:20%; left:40%; width:20%; height:auto; background-color: rgb(250, 250, 250); border-radius: 8px; padding: 20px;">
    <h3>Update Kills and Position<span id="teamNameForUpdate"></span></h3>
    <form method="POST" action="">
        <input type="hidden" name="update" id="teamNameForUpdateField">
        <label for="newKills">New Kills:</label>
        <input type="number" name="kills" id="newKills" min="0" max="255" required>
        <br><br>
        <label for="newPosition">Position:</label>
        <input type="number" name="position" id="newPosition" min="1" max="16" required>
        <br><br>
        <button type="submit"style='background-color:green;padding:10px;border-radius:5px;font-size:15px;'>Update</button>
        <button style='background-color:red;padding:10px;border-radius:5px;font-size:15px;'type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<script>
// Function to open the modal with team details
function openUpdateKillsModal(teamName, currentKills) {
    document.getElementById('updateKillsModal').style.display = 'block';
    document.getElementById('teamNameForUpdate').innerText = teamName;
    document.getElementById('newKills').value = 0; // Reset kills input to 0 for new entry
    document.getElementById('newPosition').value = 1; // Reset position input
    document.getElementById('teamNameForUpdateField').value = teamName;
}

// Function to close the modal
function closeModal() {
    document.getElementById('updateKillsModal').style.display = 'none';
}
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
    padding-top: 30px;
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
    color: #bdc3c7;
}

/* Inputs and selects */
input, select {
    width: 100%;
    background-color: #2E2E2E;
    color: white;
    border: 1px solid #444444;
    border-radius: 15px;
    padding: 18px;
    font-size: 20px;
}

input:focus, select:focus {
    border-color: #E74C3C;
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

/* Message box */
.message {
    text-align: center;
    font-size: 18px;
    margin-bottom: 20px;
    color: #bdc3c7;
}

/* Table Styling */
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

/* Row hover effect */
table tr:hover {
    background-color: #f1c40f;
    cursor: pointer;
}

/* Delete button for teams */
.delete-btn {
    background: #ff0000;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-btn:hover {
    background: #cc0000;
}

/* Image for team */
.current-files img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 10px;
}
</style>
