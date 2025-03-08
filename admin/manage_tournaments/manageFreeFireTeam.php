<?php
include_once '../connection.php'; // Database connection

// Fetch the latest tournament's ID
$stmt = $conn->prepare("SELECT MAX(tournament_id) AS latest_tournament FROM ff_team_registration");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$latest_tournament_id = $row['latest_tournament'];
$stmt->close();

if ($latest_tournament_id) {
    $stmt = $conn->prepare("SELECT team_name, user_email, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid
    FROM ff_team_registration 
    WHERE tournament_id = ? 
    ORDER BY team_name ASC");

    $stmt->bind_param("i", $latest_tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2> Tournament ID: $latest_tournament_id </h2>";
        echo "<table border='1' cellspacing='0' cellpadding='8' style='width:70%;margin-left:200px;'>
                <tr>
                    <th>Team Name</th>
                    <th>User Email</th>
                    <th>Players</th>
                    <th>Action</th>
                </tr>";

        while ($team = $result->fetch_assoc()) {
            // Collecting player details
            $players = json_encode([
                ['name' => $team['member1_name'], 'uid' => $team['member1_uid']],
                ['name' => $team['member2_name'], 'uid' => $team['member2_uid']],
                ['name' => $team['member3_name'], 'uid' => $team['member3_uid']],
                ['name' => $team['member4_name'], 'uid' => $team['member4_uid']]
            ]);

            echo "<tr id='team-{$team['team_name']}'>
                    <td>{$team['team_name']}</td>
                    <td>{$team['user_email']}</td>
                    <td><button class='show-players-btn' data-players='$players'>Show Players</button></td>
                    <td>
                        <form action='manage_tournaments/delete_team.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='team_name' value='{$team['team_name']}'>
                            <input type='hidden' name='user_email' value='{$team['user_email']}'>
                            <button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this team?\");'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No teams have registered for this tournament yet.</p>";
    }
    $stmt->close();
} else {
    echo "<p style='font-size:22px;'>No teams have registered for this tournament yet.</p>";
}
?>

<!-- Modal Structure -->
<div id="playersModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Players List</h3>
        <table border="1" cellspacing="0" cellpadding="8" id="playersTable">
            <tr>
                <th>Player Name</th>
                <th>UID</th>
            </tr>
        </table>
    </div>
</div>

<style>
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}
.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
}
.close-btn {
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close-btn:hover { color: red; }
button { padding: 5px 10px; border-radius: 5px; cursor: pointer; }
.show-players-btn { background-color: green; color: white; }
.delete-btn { background-color: red; color: white; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Show Players Button Click Event
    $(document).on('click', '.show-players-btn', function() {
        let players = JSON.parse($(this).attr('data-players'));
        $('#playersTable').empty().append('<tr><th>Player Name</th><th>UID</th></tr>');

        players.forEach(player => {
            if (player.name && player.uid) {
                $('#playersTable').append('<tr><td>' + player.name + '</td><td>' + player.uid + '</td></tr>');
            }
        });

        $('#playersModal').fadeIn();
    });

    // Close Modal
    $('.close-btn').click(() => $('#playersModal').fadeOut());
    $(window).click(event => {
        if (event.target == document.getElementById('playersModal')) {
            $('#playersModal').fadeOut();
        }
    });
});
</script>   