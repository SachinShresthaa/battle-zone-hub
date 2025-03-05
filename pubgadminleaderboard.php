<?php
include_once 'connection.php'; // Database connection

// Get tournament ID from the URL (for example, via GET parameter)
$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : 0;

// Fetch recent team names, kills, positions, and points for a specific tournament
$stmt = $conn->prepare("
    SELECT team_name, kills, position, points 
    FROM pubg_team_registration 
    WHERE tournament_id = ?
");
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Kills & Position</title>
    <style>
        /* Add your existing CSS here */
    </style>
</head>
<body>
    <h2>Enter Kills & Position for Teams</h2>
    <form action="save_kills_position.php" method="POST">
    <table border="1">
        <tr>
            <th>Team Name</th>
            <th>Kills</th>
            <th>Position</th>
            <th>Total Points</th>
            <th>Update</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { 
            $teamName = htmlspecialchars($row['team_name']);
            $prevKills = intval($row['kills']);
            $prevPosition = intval($row['position']);
            $prevPoints = intval($row['points']);
        ?>
            <tr>
                <td><?php echo $teamName; ?></td>
                <td>
                    <input type="number" name="kills[<?php echo $teamName; ?>]" min="0" value="<?php echo $prevKills; ?>">
                </td>
                <td>
                    <input type="number" name="position[<?php echo $teamName; ?>]" min="1" value="<?php echo $prevPosition; ?>">
                </td>
                <td><?php echo $prevPoints; ?></td> <!-- Display previous total points -->
                <td>
                    <button type="submit" name="update[<?php echo $teamName; ?>]" value="Update">Update</button>
                </td>
            </tr>
        <?php } ?>
    </table>
</form>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
