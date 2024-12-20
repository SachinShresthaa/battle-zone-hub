
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="signup.css" rel="stylesheet">
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
                    
                    <form method="POST" action="home.php">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                        
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>

                        <label for="password">Confirm Password</label>
                        <input type="password" id="password" name="c_password" placeholder="Confirm password" required>

                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>
                            
                        <div class="terms-and-condition">
                            <input type="checkbox" id="tc" required>
                            <label for="tc">I agree to BattleZoneHub's <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</label>
                        </div>

                        <button type="submit">Sign up</button>
                    </form>

                    <div class="login-link">
                        <p>Already have an account? <a href="login.php">Login</a></p>
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
