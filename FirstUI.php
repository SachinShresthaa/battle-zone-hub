<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Page</title>
    <link href="FirstUI.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <div class="Heading">
            <div class="TOGETHER-name-logo">
                <div class="Logo">
                    <img src="PICTURE AND LOGO/PROJECT LOGO.png" alt="logo">
                </div>
                <div class="web-name">
                    <h1>BattleZoneHub</h1>
                </div>
            </div>
            <div class="Login-button">
                <a href="login.php">
                    <button type="button">Login</button>
                </a>
            </div>
        </div>
        <div class="Body">
            <div class="leftSide">
                <h1>Play<br>Esports tournaments<br>& win rewards</h1>
                <p>Your first step to becoming a gamer!</p>
                <div class="Signup-button">
                    <a href="signup.php">
                        <button>Sign up for Free</button>
                    </a>
                </div>
            </div>
            <div class="rightSideImage">
                <img src="PICTURE AND LOGO/BACKGROUND PHOTO.png" alt="game image">
            </div>
        </div>
        <?php
        include "Footer.php"
        ?>
    </div>
</body>
</html>