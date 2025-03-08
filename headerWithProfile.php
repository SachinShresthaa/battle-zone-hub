<?php

if(session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

    include 'connection.php';
    $isAdminPage = false;
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, '/admin') === 0) {
      $isAdminPage = true;
    }
$isAdminUser = false;
if (isset($_SESSION['user_id'])) {  
    $user_id = $_SESSION['user_id'];
    $sql="SELECT * FROM users WHERE id='$user_id'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
        $user =mysqli_fetch_assoc($result);
        if($user['isadmin'] == 1){
            $isAdminUser = true;
        }else {
            if(!$isAdminPage){
                header("Location:../unauthorized.html");
                echo "Unauthorized";    
            }
        }
    }
}

// Check if the user is logged in by verifying session variables
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Fetch the user's full name and email from the database
    $query = "SELECT fullname, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_full_name, $user_email);
    $stmt->fetch();
    $stmt->close();

    // Ensure the full name and email are set in session if not already
    if (!isset($_SESSION['fullname']) && isset($user_full_name)) {
        $_SESSION['fullname'] = $user_full_name;
    }
    if (!isset($_SESSION['user_email']) && isset($user_email)) {
        $_SESSION['user_email'] = $user_email;
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user_id'];

    // Prepare the query to delete the account
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        session_destroy();  // Destroy the session after account deletion
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
            padding: 20px 60px;
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

        .edit-option {
            border: 2px solid rgb(166, 164, 164);
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
            background-color: rgb(246, 160, 160);
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
                    <span style="color: black;"><?php echo ($_SESSION['fullname']); ?></span>
                </div>
                <div class="edit-option">
                    <?php if($isAdminUser == true){ ?>
                    <div class="admin-panel">
                        <a href="./admin"><h4>Admin Panel<h4></a>
                    </div>  
                    <?php } ?>

                    <div class="dropdown-item" onclick="toggleSubmenu('submenu-settings')">
                        <img src="./assets/setting.png" alt="" class="logo">
                        <span>Settings</span>
                    </div>
                    <div id="submenu-settings" class="submenu">
                        <form method="POST" id="deleteAccountForm" style="margin: 0;">
                            <a class="dropdown-item" onclick="confirmDelete()"><span>Delete Account?</span></a>
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
