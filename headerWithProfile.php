<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user_id']; 
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: firstUi.php");
        exit();
    } else {
        $error_message = "Error deleting account. Please try again.";
    }
}
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
            background: none;
            border: none;
            cursor: pointer;
        }

        .profile-logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .profile-info span {
            font-size: 22px;
            font-weight: bold;
            color: black;
        }

        .edit-option span {
            font-size: 22px;
            padding-left: 20px;
            color: black;
        }
        .edit-option{
            border: 2px solidrgb(166, 164, 164);
        }
        .dropdown-content {
            border: 2px solid #ddd;
            display: none;
            position: absolute;
            right: 0;
            background-color: #d4d2d2;
            min-width: 250px;
            border-radius: 8px;
            z-index: 100;
            margin-top: 10px;
        }

        .submenu {
            margin-left: 20px;
            display: none;
            background-color:rgb(246, 160, 160);
            border: 2px solid gray;
        }
        .show {
            display: block;
        }

        .profile-info {
            padding: 15px;
            border-bottom: 2px solid #ddd;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .dropdown-item {
            padding: 14px 15px;
            text-decoration: none;
            display: block;
            color: black;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: rgb(255, 255, 255);
        }

        .logo {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .profile-image-container {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            </button>
            <div class="dropdown-content" id="myDropdown">
                <div class="profile-info">
                    <div class="profile-image-container">
                        <img id="profile-image" src="<?php echo $user['profile_image'] ?? 'ASSETS/profile.png'; ?>" alt="Profile Picture">
                        <input type="file" id="image-upload" name="profile_image" accept="image/*" style="display: none;">
                        <div class="overlay" onclick="document.getElementById('image-upload').click();"></div>
                    </div>
                    <span><?php echo ($_SESSION['fullname']) ?></span>
                </div>
                <div class="edit-option">
                    <div class="dropdown-item" onclick="toggleSubmenu('submenu-settings')">
                        <img src="./assets/setting.png" alt="" class="logo">
                        <span>Settings</span>
                    </div>
                    <div id="submenu-settings" class="submenu">
                        <form method="POST" id="deleteAccountForm" style="margin: 0;">
                            <a  class="dropdown-item" onclick="confirmDelete()"><span>Delete Account?</span></a>
                            <input type="hidden" name="delete_account" value="1">
                        </form>
                    </div>
                    <a href="logout.php" class="dropdown-item">
                        <img src="./admin/assets/logout.png" alt="" class="logo">
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            submenu.classList.toggle("show");
        }
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your account?")) {
                document.getElementById('deleteAccountForm').submit();
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-trigger') &&
                !event.target.matches('.profile-trigger *') &&
                !event.target.matches('.dropdown-item') &&
                !event.target.matches('.dropdown-item *')) {
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
