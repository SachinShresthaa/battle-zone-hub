<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BattleZoneHub Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="header">
    <div class="logo">  </div>
    <div class="nav">
        <a href="#">Dashboard</a>
        <a href="#">Users</a>
        <a href="#">Tournaments</a>
        <a href="#">Room Cards</a>
        <a href="#">Leaderboard</a>
        <a href="#">Share Live</a>
        <a href="#">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Tournament ID: 2</h2>
    <table border="1" cellspacing="0" cellpadding="8" style="width:70%; margin-left:200px;">
        <tr>
            <th>Team Name</th>
            <th>User Email</th>
            <th>Players</th>
            <th>Action</th>
        </tr>

        <!-- Teams will be fetched dynamically -->
        <tr id="team-Demon">
            <td>Demon</td>
            <td>aashish@gmail.com</td>
            <td><button class="toggle-btn" style="background-color:green; padding:5px; border-radius:5px;">Show Players</button></td>
            <td><button class="delete-btn" onclick="deleteTeam('Demon', 'aashish@gmail.com')">Delete</button></td>
        </tr>
        <tr class="players-row" id="players-Demon" style="display:none">
            <td colspan="4">
                <table border="1" cellspacing="0" cellpadding="8">
                    <tr>
                        <th>Player Name</th>
                        <th>UID</th>
                    </tr>
                    <tr>
                        <td>Player1</td>
                        <td>UID1</td>
                    </tr>
                    <tr>
                        <td>Player2</td>
                        <td>UID2</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<!-- Modal Structure -->
<div id="playersModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Player Details</h2>
        <table border="1" cellspacing="0" cellpadding="8" id="playersTable">
            <tr>
                <th>Player Name</th>
                <th>UID</th>
            </tr>
            <!-- Player details will be populated here -->
        </table>
    </div>
</div>

<script>
// When clicking on Show Players button
$(document).ready(function() {
    $('button.toggle-btn').click(function() {
        let teamName = $(this).closest('tr').attr('id').split('-')[1];
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

// Function to handle team deletion via AJAX
function deleteTeam(teamName, userEmail) {
    $.ajax({
        url: 'delete_team.php',
        type: 'GET',
        data: { team_name: teamName, user_email: userEmail }, // Send the correct parameters
        success: function(response) {
            let result = JSON.parse(response);

            // If the deletion is successful, remove the row from the table
            if (result.success) {
                alert(result.message);
                $('#team-' + teamName).remove();
                $('#players-' + teamName).remove();
            } else {
                alert(result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + error);
            alert('Error occurred while deleting the team. Please try again later.');
        }
    });
}
</script>

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
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
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
</style>

</body>
</html>
