<?php
include 'connection.php';

// Fetch the latest tournament's ID
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM ff_team_registration");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$latest_tournament_id = $row['latest_tournament'] ?? null;
$stmt->close();

// If no tournament is found, stop execution
if ($latest_tournament_id === null) {
    die("<p style='color: red; font-size: 18px; text-align: center;'>No tournaments found.</p>");
}

// Handle form submission to update kills, points, and total_points
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $team_id = (int)$_POST['team_id'];
    $kills = (int)$_POST['kills'];
    $points = (int)$_POST['points'];

    // Fetch current kills, points, and total_points
    $stmt = $conn->prepare("SELECT kills, points, total_points FROM ff_team_registration WHERE id = ? AND tournament_id = ?");
    $stmt->bind_param("ii", $team_id, $latest_tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team = $result->fetch_assoc();
    $current_kills = $team['kills'] ?? 0;
    $current_points = $team['points'] ?? 0;
    $current_total_points = $team['total_points'] ?? 0;

    // Calculate new kills, points, and total points
    $new_kills = $current_kills + $kills;
    $new_points = $current_points + $points;
    $new_total_points = $current_total_points + $kills + $points;

    // Update team data
    $updateStmt = $conn->prepare("UPDATE ff_team_registration SET kills = ?, points = ?, total_points = ? WHERE id = ? AND tournament_id = ?");
    $updateStmt->bind_param("iiiii", $new_kills, $new_points, $new_total_points, $team_id, $latest_tournament_id);
    if ($updateStmt->execute()) {
        echo "<script>alert('Team scores updated successfully!'); window.location.href=pubgadminleaderboard.php;</script>";
    } else {
        echo "<script>alert('Failed to update scores.');</script>";
    }
    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tournament Leaderboard</title>
    <style>
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        input {
            width: 80px;
            text-align: center;
            color: black;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Tournament Leaderboard</h1>
    <h2>Update Team Scores</h2>
    
    <table>
        <tr>
            <th>Team Name</th>
            <th>Total Kills</th>
            <th>Total Points</th>
            <th>Total (Kills + Points)</th>
            <th>Actions</th>
        </tr>

        <?php
        // Fetch teams for the latest tournament
        $stmt = $conn->prepare("SELECT id, team_name, kills, points, total_points FROM ff_team_registration WHERE tournament_id = ? ORDER BY points DESC, kills DESC");
        $stmt->bind_param("i", $latest_tournament_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($team = $result->fetch_assoc()) {
            echo "<tr>
                    <form method='POST'>
                        <td>{$team['team_name']}</td>
                        <td><input type='number' name='kills' value='0' required></td>
                        <td><input type='number' name='points' value='0' required></td>
                        <td><input type='number' value='{$team['total_points']}' disabled></td>
                        <td>
                            <input type='hidden' name='team_id' value='{$team['id']}'>
                            <button type='submit' name='update'>Update</button>
                        </td>
                    </form>
                  </tr>";
        }
        $stmt->close();
        ?>
    </table>
</div>

</body>
</html>

<div style="height:300px;"></div>