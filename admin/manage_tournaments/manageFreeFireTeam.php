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
            $players = [
                ['name' => $team['member1_name'], 'uid' => $team['member1_uid']],
                ['name' => $team['member2_name'], 'uid' => $team['member2_uid']],
                ['name' => $team['member3_name'], 'uid' => $team['member3_uid']],
                ['name' => $team['member4_name'], 'uid' => $team['member4_uid']]
            ];

            echo "<tr id='team-{$team['team_name']}'>
                    <td>{$team['team_name']}</td>
                    <td>{$team['user_email']}</td>
                    <td><button style='background-color:green;padding:5px;border-radius:5px;'class='toggle-btn'>Show Players</button></td>
                    <td><button class='delete-btn' onclick='deleteTeam(\"{$team['team_name']}\", \"{$team['user_email']}\")'>Delete</button></td>
                  </tr>";

            echo "<tr class='players-row' id='players-{$team['team_name']}' style='display:none;' >
                    <td colspan='4'>
                        <table border='1' cellspacing='0' cellpadding='8'>
                            <tr>
                                <th>Player Name</th>
                                <th>UID</th>
                            </tr>";

            foreach ($players as $player) {
                echo "<tr>
                        <td>{$player['name']}</td>
                        <td>{$player['uid']}</td>
                      </tr>";
            }

            echo "          </table>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No teams have registered for this tournament yet.</p>";
    }
    $stmt->close();
} else {
    echo "<p>No tournaments found.</p>";
}
?>

<!-- Modal Structure -->
<div id="playersModal" class="modal" style="display:none;">
    <div class="modal-content" style="width: 40%;">
        <span class="close-btn">&times;</span>
        <table border="1" cellspacing="0" cellpadding="8" id="playersTable">
            <tr>
                <th>Player Name</th>
                <th>UID</th>
            </tr>
            <!-- Player details will be populated here -->
        </table>
    </div>
</div>

<style>
/* Modal styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    /* width: 100%; */
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black with opacity */
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
#playersModel{
    width: 30%;
    height: 30%; 
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Function to handle team deletion via AJAX
function deleteTeam(teamName, userEmail) {
    if (confirm('Are you sure you want to delete this team?')) {
        $.ajax({
            url: 'delete_team.php', // PHP script to handle deletion
            type: 'GET',
            data: { team_name: teamName, user_email: userEmail }, // Send the team name and user email as GET parameters
            success: function(response) {
                console.log(response);  // Log the response for debugging
                // Parse the JSON response
                let result = JSON.parse(response);

                // If the deletion is successful, remove the row from the table
                if (result.success) {
                    alert(result.message);
                    // Remove the row from the table dynamically
                    $('#team-' + teamName).remove();
                    $('#players-' + teamName).remove();
                } else {
                    alert(result.message); // Show error message
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + error);
                alert('Error occurred while deleting the team. Please try again later.');
            }
        });
    }
}

// When clicking on Show Players button
$(document).ready(function() {
    $('button.toggle-btn').click(function() {
        let teamName = $(this).closest('tr').attr('id').split('-')[1];

        // Get the player details
        let playersRow = $('#players-' + teamName).find('table tr');
        
        // Clear any existing player data in the modal
        $('#playersTable').empty().append('<tr><th>Player Name</th><th>UID</th></tr>');

        // Populate the modal with player details
        playersRow.each(function() {
            let playerName = $(this).find('td:nth-child(1)').text();
            let playerUid = $(this).find('td:nth-child(2)').text();

            if (playerName && playerUid) {
                $('#playersTable').append('<tr><td>' + playerName + '</td><td>' + playerUid + '</td></tr>');
            }
        });

        // Show the modal
        $('#playersModal').show();
    });

    // When clicking on the close button, close the modal
    $('.close-btn').click(function() {
        $('#playersModal').hide();
    });

    // When clicking anywhere outside the modal, close the modal
    $(window).click(function(event) {
        if (event.target == document.getElementById('playersModal')) {
            $('#playersModal').hide();
        }
    });
});
</script>
