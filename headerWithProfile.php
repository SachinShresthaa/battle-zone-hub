<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BattleZoneHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .Heading {
            background-color: #2E2E2E;
            padding: 30px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .TOGETHER-name-logo {
            display: flex;
            align-items: center;
        }

        .Logo img {
            width: 75px;
            height: auto;
        }

        .web-name h1 {
            font-size: 36px;
            margin-left: 15px;
            color: white;
        }

        .profile {
            position: relative;
            display: inline-block;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 15px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .profile-logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .profile h1 {
            color: white;
            font-size: 24px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: red;
            min-width: 250px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            border-radius: 8px;
            z-index: 1;
            margin-top: 10px;
        }

        .show {
            display: block;
        }

        .profile-info {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .profile-info-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .profile-info-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .profile-info-header span {
            font-weight: bold;
        }

        .dropdown-item {
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            color: black;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        .logout {
            color: #ff0000;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="Heading">
        <div class="TOGETHER-name-logo">
            <div class="Logo">
                <img src="./ASSETS/PROJECT-LOGO.png" alt="logo">
            </div>
            <div class="web-name">
                <h1>BattleZoneHub</h1>
            </div>
        </div>
        <div class="profile">
            <button class="profile-trigger" onclick="toggleDropdown()">
                <div class="profile-logo">
                    <img src="./ASSETS/profile.png" alt="profile">
                </div>
                <h1>Profile</h1>
            </button>
            <div class="dropdown-content" id="myDropdown">
                <div class="profile-info">
                    <div class="profile-info-header">
                        <img src="./ASSETS/profile.png" alt="profile">
                        <span> <?php echo ($_SESSION['fullname'])?></span>
                    </div>
                </div>
                <a href="settings.php" class="dropdown-item">Settings & privacy</a>
                <a href="logout.php" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.profile-trigger') && 
                !event.target.matches('.profile-trigger *')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>