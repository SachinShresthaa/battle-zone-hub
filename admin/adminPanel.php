<?php
include 'header.php';
?>

<div class="main">
    <div class="nav-body">
        <nav class="nav-main">
            <h1>Admin Panel</h1>
            <ul>
                <li><a href="#addTournament">Add Tournament</a></li>
                <li><a href="#manageYouTube">Manage YouTube Live</a></li>
                <li><a href="#viewMatches">View Registered Matches</a></li>
                <li><a href="#updateLeaderboard">Update Leaderboard</a></li>
                <li><a href="#manageUsers">Manage Users</a></li>
            </ul>
        </nav>
    </div>

    <div id="addTournament">
        <h2>Add Tournament</h2>
        <form action="addTournament.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="thumbnail">Thumbnail:</label>
                <input type="file" id="thumbnail" name="thumbnail" required>
            </div>
            <div>
                <label for="tournamentName">Tournament Name:</label>
                <input type="text" id="tournamentName" name="tournamentName" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <button type="submit">Add Tournament</button>
        </form>
    </div>

    <div id="manageYouTube">
        <h2>Manage YouTube Live</h2>
        <form action="manageYouTube.php" method="POST">
            <div>
                <label for="youtubeLink">YouTube Link:</label>
                <input type="text" id="youtubeLink" name="youtubeLink" required>
            </div>
            <button type="submit">Add YouTube Live</button>
        </form>
    </div>

    <div id="viewMatches">
        <h2>View Registered Matches</h2>
        <!-- Code to display registered matches will go here -->
    </div>

    <div id="updateLeaderboard">
        <h2>Update Leaderboard</h2>
        <form action="updateLeaderboard.php" method="POST">
            <div>
                <label for="userId">User ID:</label>
                <input type="text" id="userId" name="userId" required>
            </div>
            <div>
                <label for="score">Score:</label>
                <input type="number" id="score" name="score" required>
            </div>
            <button type="submit">Update Score</button>
        </form>
    </div>

    <div id="manageUsers">
        <h2>Manage Users</h2>
        <!-- Code to manage users will go here -->
    </div>
</div>

<?php
include 'footer.php';
?>
