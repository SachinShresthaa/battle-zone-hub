<?php
session_start();
include_once 'connection.php';
include 'Header.php';

// Fetch the latest tournament's ID
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM ff_team_registration");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$latest_tournament_id = $row['latest_tournament'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Leaderboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Jacques Francois', serif;
            color: white;
        }

        body {
            background: black;
            text-align: center;
        }

        .container {
            width: 85%;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            padding-left: 250px;
        }

        .container h1 {
            margin-bottom: 30px;
            letter-spacing: 2px;
            padding-right: 250px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background: #d4d2d2;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            color: black;
            font-size: 18px;
            border: 2px solid white;
            text-align: center;
        }

        th {
            background-color: #2E2E2E;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Styles for top 3 ranks */
        .gold { background-color: gold; font-weight: bold; }
        .silver { background-color: silver; font-weight: bold; }
        .bronze { background-color: #cd7f32; font-weight: bold; }

        tr:hover {
            background-color: #787878;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tournament Leaderboard</h1>

    <?php
    function formatRank($rank) {
        if ($rank == 1) {
            return "🥇"; // Gold medal
        } elseif ($rank == 2) {
            return "🥈"; // Silver medal
        } elseif ($rank == 3) {
            return "🥉"; // Bronze medal
        } elseif ($rank % 100 >= 11 && $rank % 100 <= 13) {
            return $rank . "th";
        }
        switch ($rank % 10) {
            case 1: return $rank . "st";
            case 2: return $rank . "nd";
            case 3: return $rank . "rd";
            default: return $rank . "th";
        }
    }

    if ($latest_tournament_id) {
        echo "<table>
                <tr>
                    <th>Rank</th>
                    <th>Team Name</th>
                    <th>Total Kills</th>
                    <th>Total Points</th>
                </tr>";

        $stmt = $conn->prepare("SELECT team_name, kills, points 
                                FROM ff_team_registration 
                                WHERE tournament_id = ? 
                                ORDER BY points DESC, kills DESC");
        $stmt->bind_param("i", $latest_tournament_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $rank = 1;
        while ($team = $result->fetch_assoc()) {
            // Assign classes based on rank
            $rowClass = ($rank == 1) ? "gold" : (($rank == 2) ? "silver" : (($rank == 3) ? "bronze" : ""));

            echo "<tr class='$rowClass'>
                    <td>" . formatRank($rank) . "</td>
                    <td>{$team['team_name']}</td>
                    <td>{$team['kills']}</td>
                    <td>{$team['points']}</td>
                  </tr>";
            $rank++;
        }
        echo "</table>";
    } else {
        echo "<p>No tournaments found.</p>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
<div style="height:300px;"></div>
<?php
include 'Footer.php';
?>
