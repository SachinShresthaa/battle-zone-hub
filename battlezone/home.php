<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="./CSS/home.css" rel="stylesheet">
</head>
<body>
    <div class="Main">
        <?php
            include "HeaderWithProfile.php"
        ?>
        <div class="Body">
            <h2>GAMES</h2>
            <p>Choose your favorite game, enter tournaments, <br>showcase your skills, and claim your rewards!</p>
            <div class="games">
                    <a href="FreeFire.php">
                        <img src="./ASSETS/home-ff.png" alt="Free Fire">
                        <h3>FREE FIRE</h3>
                    </a>
                    <a href="Pubg.php">
                        <img src="./ASSETS/home-pubg.png" alt="PUBG">
                        <h3>PUBG</h3>
                    </a>
            </div>
            <div class="dowm-content">
                <div class="box">

                    <h1 style="text-align:center;"> We are adding more games in future</h1>
                    <div class="future-games">
                        <img src="./ASSETS/coc.jpg">
                        <img src="./ASSETS/COD.jpg">
                        <img src="./ASSETS/mlbb.jpg">
                        <img src="./ASSETS/fortnite.jpg">
                        <img src="./ASSETS/valorant.jpg">
                    </div>

                    <div class="werinpubg" style="display:flex;padding-top:100px">
                        <img src="assets/werinpubg.png">
                        <div class="text"style="padding-left:200px;">
                            <h1 style="font-size:40px;padding-bottom:10px;">PUBG: Players Unknown Battle Grounds</h1>
                            <p1>Pubg is a player versus player shooter game in <br>which up to one hundred players fight in a battle <br>royale, a type of large-scale last man standing <br>deathmatch where players fight to remain the last <br>alive.</p1>
                            <div style="display:flex;">
                                <h1>We are here to provide tournament<br> platform for you</h1>
                                <button> View Now</button>
                            </div>
                        </div>
                    </div>

                    <div class="werinpubg" style="display:flex;">
                        <div class="text" style="padding-left:50px;padding-top:100px;">
                                <h1 style="font-size:40px;padding-bottom:10px;">FREE FIRE</h1>
                                <p1>Free Fire is the ultimate survival shooter game <br>available on mobile. Each 10-minute game places <br>you on a remote island where you are pit against 49<br> other players, all seeking survival.</p1>
                                <div style="display:flex;">
                                    <h1>We are here to provide tournament<br> platform for you</h1>
                                    <button> View Now</button>
                                </div>
                        </div>
                        <img style="height:500px;padding-right:150px;"src="assets/werinff.png">
                    </div>
                    
                    <div class="werinpubg" style="display:flex;">
                        <img style="height:500px;
                                    padding-right:150px;"src="assets/aboutus.png">
                        <div class="text"style="padding-left:100px;">
                            <h1 style="font-size:40px;padding-bottom:10px;">About Us</h1>
                            <p1>Your ultimate destination for competitive gaming and <br>esports tournaments. At BattleZoneHub, we bring <br>together passionate gamers from across the globe to <br>showcase their skills, connect with fellow enthusiasts, <br>and compete for glory in the thrilling worlds of PUBG <br>and Free Fire.</p1>
                            <button style="display:block; margin-left:250px;"> Learn More</button>
                        </div>
                    </div>

                    <div class="werinpubg" style="display:flex;">
                        <div class="text" style="padding-left:50px;padding-top:100px;">
                                <h1 style="font-size:40px;padding-bottom:10px;">Contact Us</h1>
                                <p1>At BattleZoneHub, we’re here to help! Whether you have questions, feedback, or need assistance, feel free to reach out:</p1>
                                <ul><br>
                                    <li><strong>Gmail:</strong> battlezonehub@gmail.com</li>
                                    <li><strong>Phone:</strong> +977 9869111467</li>
                                </ul>
                                <div style="display:flex;">
                                    <h1>Chat with us on discord</h1>
                                    <button> Join Now</button>
                                </div>
                        </div>
                        <img style="height:500px;padding-right:150px;"src="assets/contact.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
<?php
            include "Footer.php"
        ?>