<?php
session_start();
$error = ""; 
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error']; 
    unset($_SESSION['error']); 
}

// Assuming that user authentication has been done correctly
// Set session email after successful login
if (isset($user_email)) {
    $_SESSION['email'] = $user_email;  // Store the user email in session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="./CSS/login.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="Main">
            <?php
                include "Header.php";
            ?>
        <div class="Body">
            <div class="leftSide">
                <div class="login-details">
                    <h2>Hey<br>Welcome Back!</h2>
    
                    <form method="POST" action="login_insert.php">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <div id="status" class="error-message" style="color: red; margin-top: 10px;"></div>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>

                        <?php if (!empty($error)): ?>
                            <div class="error-message" style="color: red; margin-top: 10px;">
                                <?= htmlspecialchars($error) ?>
                            </div>
                         <?php endif; ?>
                        <button type="submit" name="login">Login</button>
                    </form>

                    <div class="signup-link">
                        <p>Forget your password?</p>
                        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                    </div>
                </div>
            </div>
            <div class="rightSideImage">
                <img src="./ASSETS/BACKGROUND.png" alt="game image">
            </div>
        </div>
        <?php 
        include "Footer.php";
        ?>
    </div>
    <script>

$(document).ready(function() {
        let timeoutId;
        
        $('#email').on('input', function() {
            clearTimeout(timeoutId);
            const email = $(this).val();
            const statusDiv = $('#status');
            if (!email) {
                statusDiv.html('');
                return;
            }
            timeoutId = setTimeout(function() {
                $.ajax({
                    url: 'authenticationLog.php',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.exists) {
                            statusDiv.html('')
                                   .removeClass('error')
                                   .addClass('success');
                        } else {
                            statusDiv.html('Email is not registered!')
                                   .removeClass('sucess')
                                   .addClass('error');
                        }
                    },
                    error: function() {
                        statusDiv.html('Error checking email')
                               .removeClass('success')
                               .addClass('error');
                    }
                });
            }, 500);
        });
    });

</script>
</body>
</html>
</html>
