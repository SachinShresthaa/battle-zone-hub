<?php
include_once '../connection.php';

// Get all available tournament IDs
$tournamentQuery = "
    SELECT DISTINCT tournament_id 
    FROM (
        SELECT tournament_id FROM pubg_team_registration
        UNION
        SELECT tournament_id FROM ff_team_registration
    ) AS combined_tournaments 
    ORDER BY tournament_id";
$tournamentResult = $conn->query($tournamentQuery);

// Get the selected tournament and game type
$selected_tournament = isset($_POST['tournament_id']) ? intval($_POST['tournament_id']) : 
    ($tournamentResult->num_rows > 0 ? $tournamentResult->fetch_assoc()['tournament_id'] : 0);
$selected_game = isset($_POST['game_type']) ? $_POST['game_type'] : '';

// Query to get team data if game is selected
$teamsResult = null;
if ($selected_game && $selected_tournament) {
    $table = ($selected_game === 'PUBG') ? 'pubg_team_registration' : 'ff_team_registration';
    $teamsQuery = "
        SELECT 
            id,
            team_name,
            kills,
            points,
            (@row_number:=@row_number + 1) AS position
        FROM $table, (SELECT @row_number:=0) AS r
        WHERE tournament_id = ?
        ORDER BY points DESC, kills DESC";
    
    $teamsStmt = $conn->prepare($teamsQuery);
    $teamsStmt->bind_param("i", $selected_tournament);
    $teamsStmt->execute();
    $teamsResult = $teamsStmt->get_result();
}

// Reset tournament result pointer for the dropdown
$tournamentResult->data_seek(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Leaderboard</title>
    <style>
        .container { 
            padding: 20px; 
            font-family: Arial, sans-serif; 
            max-width: 1200px;
            margin: 0 auto;
        }
        h1, h2 { 
            text-align: center; 
            color: #333; 
        }
        .selector-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        .game-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-pubg {
            background-color: #1a73e8;
        }
        .btn-ff {
            background-color: #e84c1a;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #333; 
            color: white; 
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
        }
        .position {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Tournament Leaderboard</h1>

    <!-- Tournament Selector -->
    <div class="selector-container">
        <form method="POST" id="gameForm">
            <select name="tournament_id" onchange="this.form.submit()">
                <?php while ($tournament = $tournamentResult->fetch_assoc()): ?>
                    <option value="<?php echo $tournament['tournament_id']; ?>" 
                            <?php echo ($selected_tournament == $tournament['tournament_id']) ? 'selected' : ''; ?>>
                        Tournament ID: <?php echo htmlspecialchars($tournament['tournament_id']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <!-- Game Selection Buttons -->
    <div class="game-buttons">
        <form method="POST">
            <input type="hidden" name="tournament_id" value="<?php echo $selected_tournament; ?>">
            <input type="hidden" name="game_type" value="PUBG">
            <button type="submit" class="btn btn-pubg">PUBG</button>
        </form>
        <form method="POST">
            <input type="hidden" name="tournament_id" value="<?php echo $selected_tournament; ?>">
            <input type="hidden" name="game_type" value="FF">
            <button type="submit" class="btn btn-ff">Free Fire</button>
        </form>
    </div>

    <!-- Teams Data Table -->
    <?php if ($selected_game && $teamsResult && $teamsResult->num_rows > 0): ?>
        <h2><?php echo htmlspecialchars($selected_game); ?> Teams Leaderboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Team Name</th>
                    <th>Kills</th>
                    <th>Points</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $teamsResult->fetch_assoc()): ?>
                <tr>
                    <td class="position"><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['kills']); ?></td>
                    <td><?php echo htmlspecialchars($row['points']); ?></td>
                    <td>
                        <button class="action-btn">Update</button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($selected_game): ?>
        <p>No teams found for the selected tournament and game type.</p>
    <?php endif; ?>
</div>
</body>
</html>