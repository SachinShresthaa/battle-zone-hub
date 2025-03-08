<?php
session_start();
include_once 'connection.php';
include 'Header.php';

// Fetch the latest tournament's ID for PUBG
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM pubg_team_registration");
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
    <title>PUBG Tournament Leaderboard</title>
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
            padding: 12px;
            color: black;
            font-size: 20px;
            border: 2px solid white;
            font-weight: bold;
            text-align: center;
        }
        th {
            background-color: #2E2E2E;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }
        /* Background Colors for Top 3 Ranks */
        .gold { background-color: hsl(56, 70.80%, 54.30%); font-weight: bold; }
        .silver { background-color: hsl(60, 5.00%, 92.20%); font-weight: bold; }
        .bronze { background-color: hsl(31, 33.30%, 71.20%); font-weight: bold; }
        .medal {
            font-size: 25px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>PUBG Tournament Leaderboard</h1>

    <?php
    function formatRank($rank) {
        if ($rank % 100 >= 11 && $rank % 100 <= 13) {
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
                    <th>Kills</th>
                    <th>Points</th>
                    <th>Total Points</th>
                </tr>";

        // Fixed SQL query (removed stray comma)
        $stmt = $conn->prepare("SELECT team_name, kills, points, total_points FROM pubg_team_registration 
                                WHERE tournament_id = ? 
                                ORDER BY points DESC, kills DESC");
        $stmt->bind_param("i", $latest_tournament_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $rank = 1;
        while ($team = $result->fetch_assoc()) {
            // Assign background class based on rank
            $class = "";
            $medal = "";
            if ($rank == 1) {
                $class = "gold";
                $medal = "<span class='medal'>🥇</span>";
            } elseif ($rank == 2) {
                $class = "silver";
                $medal = "<span class='medal'>🥈</span>";
            } elseif ($rank == 3) {
                $class = "bronze";
                $medal = "<span class='medal'>🥉</span>";
            } else {
                $medal = formatRank($rank);
            }

            echo "<tr class='{$class}'>
                    <td>{$medal}</td>
                    <td>{$team['team_name']}</td>
                    <td>{$team['kills']}</td>
                    <td>{$team['points']}</td>
                    <td>{$team['total_points']}</td>
                  </tr>";
            $rank++;
        }
        echo "</table>";
        $stmt->close();
    } else {
        echo "<p>No PUBG tournaments found.</p>";
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
