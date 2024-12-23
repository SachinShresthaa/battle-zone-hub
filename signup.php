<?php
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="CSS/signup.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="Main">
        <?php
            include "Header.php"
        ?>
        <div class="Body">
            <div class="leftSide">
                <div class="signup-details">
                    <h2>Create Account</h2>
                    
                    <form method="POST" action="signup_insert.php">
                        <label for="fname">Full Name</label>
                        <input type="text" id="fname" name="fname" placeholder="Enter your Full Name">
                        
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_GET['signup']) && $_GET['signup'] == 'success' ? '' : ''; ?>">
                        <div id="email_msg"></div>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>

                        <div class="" id="msg"></div>

                        <label for="cpassword">Confirm Password</label>
                        <input type="password" id="c_password" name="cpassword" placeholder="Confirm password" required>

                        <div id="message" class="error_msg"></div>

                        <div class="terms-and-condition">
                            <input type="checkbox" id="tc" required>
                            <label for="tc">I agree to BattleZoneHub's <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</label>
                        </div>

                        <button type="submit" name="submit" id="button">Sign up</button>
                    </form>

                    <div class="login-link">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
            <div class="rightSideImage">
                <img src="ASSETS/BACKGROUND.png" alt="game image">
            </div>
        </div>
        <?php 
        include "Footer.php";
        ?>
    </div>
    <script src="authentication.js"></script>
</body>
</html>