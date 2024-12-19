<?php 
require_once("connection.php"); // Database connection
session_start(); // Start session to manage user login state

$error = ""; // Initialize error message

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Input validation
    if (empty($email) || empty($password)) {
        $error = "Email and Password are required!";
    } else {
        // Check if the user exists in the database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the home page
                header("Location: home.php");
                exit;
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "No account found with this email.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="login.css" rel="stylesheet">
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
        </div>
        <div class="Body">
            <div class="leftSide">
                <div class="login-details">
                    <h2>Hey<br>Welcome Back!</h2>
                    
                    <!-- Display error messages -->
                    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>

                    <form method="POST" action="">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            
                        <button type="submit">Login</button>
                    </form>
                    <div class="signup-link">
                        <p>Forget your password?</p>
                        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                    </div>
                </div>
            </div>
            <div class="rightSideImage">
                <img src="PICTURE AND LOGO/BACKGROUND PHOTO.png" alt="game image">
            </div>
        </div>
        <?php 
        include "Footer.php";
        ?>
    </div>
</body>
</html>
