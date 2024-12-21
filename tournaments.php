<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments</title>
    <link href="./CSS/tournaments.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .Main {
            display: flex;
            flex-direction: column;

            text-align: left;
        }

        h1 { 
            font-size: 40px;
            text-align: left;
            font-weight: bold;
            color: #ffffff;
            padding: 40px;
            padding-left: 130px;
        }

        .line {
            width: 100%;
            height: 3px; 
            /* margin-top: 0px; */
            background-color: white; 
            /* margin-bottom: 20px;  */
        }

        .Body {
            width: 80%;
            padding-left: 130px;
            margin-top: 100px;
            margin-bottom: 100px;

        }

        .box {
            display: flex;
            justify-content: space-between;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .leftSide, .rightSide {
            flex: 1;
            padding: 20px;
        }
        .leftSide {
            width: 800px;
        }

        .rightSide {
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rightSide img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="Main">
        <?php
        include "Header.php";
        ?>
        <h1>Available Tournaments</h1>
        <div class="line"></div> 
        <div class="Body">
            <div class="box">
                <div class="leftSide">
                </div>
                <div class="rightSide">
                </div>
            </div>
        </div>
        <?php
        include "Footer.php";
        ?>
    </div>
</body>
</html>
