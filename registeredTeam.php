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
<body>
    <div class="main">
        <h1>Registered Teams</h1>
        <div class="line"></div>
        
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
