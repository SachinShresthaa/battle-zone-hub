<?php
require_once("../connection.php");
session_start();

// Redirect if already logged in

// Process login form submission
$error_message = '';
if (isset($_POST["login"])) {
    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    if ($username && $password) {
        // Query using existing table structure
        $query = "SELECT * FROM admin_users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Set session variables
            $_SESSION['AdminLoginId'] = $row['username'];
            
            // Redirect to admin panel
            header("Location: adminPanel.php");
            exit();
        } else {
            $error_message = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="./CSS/adminlogin.css" rel="stylesheet">
</head>

<body>
    <div class="Main">
        <?php include "Header.php"; ?>
        
        <div class="Body">
            <div class="leftSide">
                <div class="login-details">
                    <h2>Admin Login</h2>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Enter your username" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password" 
                                   required>
                        </div>

                        <?php if ($error_message): ?>
                            <div class="error-message">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
            </div>
            
            <div class="rightSideImage">
                <img src="./ASSETS/BACKGROUND.png" alt="Admin Login Image">
            </div>
        </div>
        
        <?php include "Footer.php"; ?>
    </div>
</body>
</html>