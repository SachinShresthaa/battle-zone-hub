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
            <?php
                include "Header.php"
            ?>
        <div class="Body">
            <div class="leftSide">
                <div class="login-details">
                    <h2>Hey<br>Welcome Back!</h2>
    
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
