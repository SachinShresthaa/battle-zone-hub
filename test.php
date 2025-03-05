<?php include_once 'connection.php'; // Database connection  

$teams = []; 
$kills = []; 
$positions = []; 
$total_points = [];
$latest_tournament_id = 0;  

// Fetch the latest tournament ID 
$stmt = $conn->prepare("SELECT id FROM tournaments ORDER BY date DESC LIMIT 1"); 
if ($stmt->execute()) {     
    $result = $stmt->get_result();     
    $row = $result->fetch_assoc();     
    $latest_tournament_id = $row['id'] ?? 0;     
    $stmt->close(); 
}  

// Fetch team names, kills, positions, and total points for the latest tournament 
if ($latest_tournament_id > 0) {     
    $stmt = $conn->prepare("         
        SELECT team_name, kills, position, total_points          
        FROM leaderboard          
        WHERE tournament_id = ?     
    ");     
    $stmt->bind_param("i", $latest_tournament_id);     
    $stmt->execute();     
    $result = $stmt->get_result();      
    
    while ($row = $result->fetch_assoc()) {         
        $teams[] = $row['team_name'];         
        $kills[$row['team_name']] = $row['kills']; // Store kills by team_name         
        $positions[$row['team_name']] = $row['position']; // Store position by team_name
        $total_points[$row['team_name']] = $row['total_points']; // Store total points by team_name     
    }     
    $stmt->close(); 
} 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Update Tournament Scores</title>     
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color:rgb(0, 0, 0);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color:#2e2e2e;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9f7fe;
        }

        /* Update button styling */
        .update-btn {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .update-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head> 
<body>     
    <h1>Update Scores for Latest Tournament</h1>      
    
    <?php if (empty($teams)): ?>         
        <p>No teams found for the latest tournament.</p>     
    <?php else: ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">Scores updated successfully!</div>
        <?php endif; ?>
             
        <form method="POST" action="update_leaderboard.php">             
            <table border="1" cellspacing="0" cellpadding="8" style="width:70%; margin-left:200px;">                 
                <tr>                     
                    <th>Team Name</th>                     
                    <th>Kills</th>                     
                    <th>Position</th>
                    <th>Total Points</th>   
                    <th>Update</th> <!-- New column for Update button -->                
                </tr>                 
                <?php foreach ($teams as $team_name) { ?>                     
                    <tr>                         
                        <td><?php echo htmlspecialchars($team_name); ?></td>                         
                        <td>                             
                            <input type="hidden" name="team_name[]" value="<?php echo $team_name; ?>">                             
                            <input type="number" name="kills[]" value="<?php echo isset($kills[$team_name]) ? htmlspecialchars($kills[$team_name]) : 0; ?>" required>                         
                        </td>                         
                        <td>                             
                            <input type="number" name="position[]" value="<?php echo isset($positions[$team_name]) ? htmlspecialchars($positions[$team_name]) : 0; ?>" required>                         
                        </td>
                        <td class="total-points">
                            <?php echo isset($total_points[$team_name]) ? htmlspecialchars($total_points[$team_name]) : 0; ?>
                        </td>                     
                        <td> 
                            <button type="submit" class="update-btn" name="update_scores" value="<?php echo $team_name; ?>">Update</button>
                        </td>
                    </tr>                 
                <?php } ?>             
            </table>             
        </form>     
    <?php endif; ?> 
</body> 
</html>
