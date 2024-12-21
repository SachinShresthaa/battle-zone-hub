<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Registration PUBG</title>
    <link rel="stylesheet" href="./CSS/teamregister.css">
</head>
<body>
    <?php
        include "Header.php"
    ?>
    <div class="main">
        <div class="left-side">
            <img src="./ASSETS/FFregister.png" alt="PUBG Registration" style="margin-top: 0px;">
        </div>
        <div class="right-side">
            <h1>Team Registration</h1>
            <form action="/register-team" method="POST">
                <label for="team-name">Team Name</label>
                <input type="text" id="team-name" name="teamName" placeholder="Enter your team name" required>

                <label for="team-logo">Your Team Logo</label>
                <input type="file" id="team-logo" name="teamLogo" accept="image/*">

                <label for="members">Team Members (Game's Username)</label>
                <div class="team-members">
                    <input type="text" id="member1" name="member1" placeholder="1. First Player (with UID)" required>
                    <input type="text" id="member2" name="member2" placeholder="2. Second Player (with UID)" required>
                    <input type="text" id="member3" name="member3" placeholder="3. Third Player (with UID)" required>
                    <input type="text" id="member4" name="member4" placeholder="4. Fourth Player (with UID)" required>
                </div>
                <p class="note">Ensure all fields are filled correctly before submitting.<br> Entry fee: NPR200</p>
                <button type="submit">Register Now</button>
            </form>    
        </div>
    </div>
    <?php
        include "Footer.php"
    ?>
</body>
</html>
