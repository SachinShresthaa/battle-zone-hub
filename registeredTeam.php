<?php 

include_once "connection.php";

// Get the tournament ID from the URL
$tournament_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch teams for the selected tournament from both FreeFire and PUBG tables
$teamsQuery = "
    SELECT team_name, member1_name, member1_uid, member2_name, member2_uid, 
           member3_name, member3_uid, member4_name, member4_uid
    FROM ff_team_registration 
    WHERE tournament_id = $tournament_id
    UNION
    SELECT team_name, member1_name, member1_uid, member2_name, member2_uid, 
           member3_name, member3_uid, member4_name, member4_uid
    FROM pubg_team_registration 
    WHERE tournament_id = $tournament_id
";
$teamsResult = $conn->query($teamsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Teams</title>
    <link rel="stylesheet" href="css/viewDetails.css">
</head>
<style>

/* Decorative Line */
.line {
    width: 180px;
    height: 5px;
    background: #e51b1b;
    margin: 10px auto 30px auto;
    border-radius: 5px;
}

/* Teams Section */
.teams-section {
    width: 85%;
    overflow-x: auto;
    padding-left:180px;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    background: #222;
    border-radius: 5px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    font-size: 20px;
    border-bottom: 2px solid white;
    text-align: center;
}

th {
    background:#243b55;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
}

td {
    background: #1e1e1e;
    color: #fff;
}

/* Hover Effect */
tr:hover {
    background: #292929;
    transition: 0.3s;
}

/* Show Players Button */
.show-players-btn {
    background: #243b55;
    border: none;
    padding: 10px 25px;
    color: white;
    font-size: 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
    text-transform: uppercase;
}

.show-players-btn:hover {
    background: white;
    transform: scale(1.05);
    color:black;
}

/* Players Details (Initially Hidden) */
.hidden-players {
    display: none;
    background:rgb(122, 35, 13);
    color: #ff6b6b;
    padding: 15px;
    border-radius: 10px;
    margin-top: 10px;
    border: 1px solid #e51b1b;
    transition: 0.3s ease-in-out;
}

/* Responsive */
@media (max-width: 768px) {
    .main {
        max-width: 95%;
    }

    h1 {
        font-size: 30px;
    }

    th, td {
        font-size: 18px;
        padding: 12px;
    }
}

.space {
    height: 50px;
}

</style>
<body>
    <div class="main">
        
        <div class="teams-section">
            <?php if ($teamsResult && $teamsResult->num_rows > 0): ?>
                <table border="1" cellspacing="0" cellpadding="8" style="width:100%; margin-top:20px;">
                    <thead>
                        <tr>
                            <th>Team Name</th>
                            <th>Players</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($team = $teamsResult->fetch_assoc()) {
                            $players = [
                                ['name' => $team['member1_name'], 'uid' => $team['member1_uid']],
                                ['name' => $team['member2_name'], 'uid' => $team['member2_uid']],
                                ['name' => $team['member3_name'], 'uid' => $team['member3_uid']],
                                ['name' => $team['member4_name'], 'uid' => $team['member4_uid']]
                            ];
                            echo "<tr>
                                    <td>" . htmlspecialchars($team['team_name']) . "</td>
                                    <td><button class='show-players-btn' onclick='togglePlayers(\"team-{$team['team_name']}\")'>Show Players</button></td>
                                  </tr>";
                            // Hidden players section
                            echo "<tr id='team-{$team['team_name']}' style='display:none;'>
                                    <td colspan='2'>
                                        <div>";
                            foreach ($players as $player) {
                                echo "<div>" . htmlspecialchars($player['name']) . " (UID: " . htmlspecialchars($player['uid']) . ")</div>";
                            }
                            echo "</div>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No teams registered for this tournament yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="space"></div>

    <script>
        // Function to toggle the visibility of players
        function togglePlayers(teamId) {
            var teamRow = document.getElementById(teamId);
            if (teamRow.style.display === 'none') {
                teamRow.style.display = 'table-row';  // Show the players
            } else {
                teamRow.style.display = 'none';  // Hide the players
            }
        }
    </script>
</body>
</html>
