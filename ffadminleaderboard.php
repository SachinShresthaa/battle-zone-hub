<?php
include_once 'connection.php'; // Database connection

// Fetch recent team names from the latest tournament
$stmt = $conn->prepare("
    SELECT DISTINCT team_name, kills, position, points 
    FROM ff_team_registration 
    WHERE tournament_id = (
        SELECT id FROM tournaments ORDER BY date DESC LIMIT 1
    )
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Kills & Position</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            font-size: 36px;
            padding-top: 30px;
            color: #E74C3C;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border: 2px solid white;
        }

        table th {
            background-color: #2E2E2E;
            color: white;
            font-size: 20px;
        }

        table td {
            background-color: #d4d2d2;
            font-size: 18px;
        }

        table tr:hover {
            background-color: #f1c40f;
            cursor: pointer;
        }

        .update-btn {
            background: rgb(229, 23, 23);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-btn:hover {
            background: rgb(255, 49, 49);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }

        .close-btn {
            background: #ff0000;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .close-btn:hover {
            background: #cc0000;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #444;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn {
            background: #2E86C1;
            color: white;
        }

        .submit-btn:hover {
            background: #1F618D;
        }
    </style>
</head>
<body>

    <h2>Enter Kills & Position for Teams</h2>

    <table border="1">
        <tr>
            <th>Team Name</th>
            <th>Kills</th>
            <th>Position</th>
            <th>Total Points</th>
            <th>Update</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr id="team-<?php echo htmlspecialchars($row['team_name']); ?>">
                <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                <td class="kills"><?php echo intval($row['kills']); ?></td>
                <td class="position"><?php echo intval($row['position']); ?></td>
                <td class="points"><?php echo intval($row['points']); ?></td>
                <td>
    <button class="update-btn" onclick="openModal(
        '<?php echo htmlspecialchars($row['team_name']); ?>',
        <?php echo intval($row['kills']); ?>,
        <?php echo intval($row['position']); ?>
    )">Update</button>
    <button class="reset-btn" onclick="resetTeamData('<?php echo htmlspecialchars($row['team_name']); ?>')">Reset</button>
</td>
            </tr>
        <?php } ?>
    </table>

    <!-- Modal Popup -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <h3>Update Kills & Position</h3>
            <input type="hidden" id="teamName">
            <label>Team: <span id="teamDisplay"></span></label><br>
            <label>Kills:</label>
            <input type="number" id="killsInput" min="0">
            <label>Position:</label>
            <input type="number" id="positionInput" min="1">
            <button onclick="saveUpdate()" class="submit-btn">Save</button>
            <button class="close-btn" onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <script>
        function openModal(teamName, kills, position) {
            document.getElementById("teamName").value = teamName;
            document.getElementById("teamDisplay").innerText = teamName;
            document.getElementById("killsInput").value = kills;
            document.getElementById("positionInput").value = position;
            document.getElementById("updateModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("updateModal").style.display = "none";
        }

        function saveUpdate() {
            let teamName = document.getElementById("teamName").value;
            let kills = document.getElementById("killsInput").value;
            let position = document.getElementById("positionInput").value;

            $.ajax({
                url: 'save_kills_position.php',
                type: 'POST',
                data: { team_name: teamName, kills: kills, position: position },
                success: function(response) {
                    let data = JSON.parse(response);
                    let row = document.getElementById("team-" + teamName);
                    row.querySelector(".kills").innerText = data.kills;
                    row.querySelector(".position").innerText = data.position;
                    row.querySelector(".points").innerText = data.points;
                    closeModal();
                }
            });
        }
    </script>
    <script>
        function resetTeamData(teamName) {
    if (confirm("Are you sure you want to reset kills, position, and points for " + teamName + "?")) {
        $.ajax({
            url: 'reset_kills_position.php',
            type: 'POST',
            data: { team_name: teamName },
            success: function(response) {
                let data = JSON.parse(response);
                let row = document.getElementById("team-" + teamName);
                row.querySelector(".kills").innerText = data.kills;
                row.querySelector(".position").innerText = data.position;
                row.querySelector(".points").innerText = data.points;
                alert("Data reset successfully for " + teamName);
            }
        });
    }
}
</script>

</body>
</html> 