<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }
    $resetCode = rand(100000, 999999);
    echo "Reset code sent to $email. Your code is $resetCode.";
}
?>
