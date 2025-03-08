<?php 
    include "Header.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Jacques Francois;
            color: white;
        }

         body  {
            background-color: #000000;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 400px;
            margin-left:39%;
        }
        .forgot-pw h1 {
            text-align: center;
            color: white;
            padding-bottom:30px;
            font-size:50px;
         }
         form label {padding-top:50px;
            text-align: left;
            font-size: 30px;
            color: white;
            padding: 7px;
            margin-top: 7px;
            margin-bottom: -12px;
            }
        form input {
            width: 100%;
            background-color: #2E2E2E;
            color: white;
            border: 1px solid #444444;
            border-radius: 15px;
            padding: 18px;
            margin-top: 7px;
            font-size: 22px;
            }
        button{
            background-color: #db1818;
            width: 100%;
            color: white;
            border-radius: 15px;
            padding: 12px;
            font-size: 25px;  
            margin-top: 30px;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color:rgb(173, 0, 0);
        }
        .forgot-pw p {
            text-align: center;
            padding-top:30px;
            color: white;
            font-size:25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="send_code.php" method="post" class="forgot-pw">
            <h1>Forgot Password?</h1>
            <div class="forgot-pw-form">
                <div class="forgot-pw-field">
                    <label for="email">Enter Your Email here!<label>
                    <input type="email" id="email" name="email" placeholder="Email Address" required>
                </div>
                <button type="submit">SEND CODE</button>
            </div>
            <p>OR</p>
        </form>
        <a href="login.php" target="_self"><button>SIGN IN</button></a>
    </div>
</body>
</html>

<?php 
    include "Footer.php"; 
?>
