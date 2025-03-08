<?php
include_once 'connection.php'; // Database connection

if (isset($_POST['update_scores'])) {
    $team_names = $_POST['team_name'] ?? [];
    $new_kills = $_POST['kills'] ?? [];
    $new_positions = $_POST['position'] ?? [];
    
    // Get latest tournament ID
    $stmt = $conn->prepare("SELECT id FROM tournaments ORDER BY date DESC LIMIT 1");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $latest_tournament_id = $row['id'] ?? 0;
        $stmt->close();
    } else {
        die("Error fetching tournament ID: " . $conn->error);
    }
    
    if ($latest_tournament_id > 0) {
        // Begin transaction for data integrity
        $conn->begin_transaction();
        
        try {
            // Prepare statement to get current values
            $get_current = $conn->prepare("SELECT kills, position FROM leaderboard WHERE tournament_id = ? AND team_name = ?");
            
            // Prepare update statement
            $update_stmt = $conn->prepare("UPDATE leaderboard SET kills = ?, position = ?, total_points = ? WHERE tournament_id = ? AND team_name = ?");
            
            // Process each team
            for ($i = 0; $i < count($team_names); $i++) {
                $team_name = $team_names[$i];
                $new_kill_value = intval($new_kills[$i]);
                $new_position_value = intval($new_positions[$i]);
                
                // Get current values
                $get_current->bind_param("is", $latest_tournament_id, $team_name);
                $get_current->execute();
                $result = $get_current->get_result();
                $current = $result->fetch_assoc();
                
                // Add new values to current values
                $updated_kills = ($current['kills'] ?? 0) + $new_kill_value;
                $updated_position = ($current['position'] ?? 0) + $new_position_value;
                
                // Calculate total points (kills + position)
                $updated_total_points = $updated_kills + $updated_position;
                
                // Update database
                $update_stmt->bind_param("iiiis", $updated_kills, $updated_position, $updated_total_points, $latest_tournament_id, $team_name);
                if (!$update_stmt->execute()) {
                    throw new Exception("Error updating team $team_name: " . $update_stmt->error);
                }
            }
            
            // Commit transaction
            $conn->commit();
            
            // Close statements
            $get_current->close();
            $update_stmt->close();
            
            // Redirect back with success message
            header("Location: test.php?success=1");
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            die("Error updating scores: " . $e->getMessage());
        }
    } else {
        die("No active tournament found.");
    }
} else {
    // Redirect back if form not submitted
    header("Location: test.php");
    exit;
}
?>
