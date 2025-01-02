<?php
include "Header.php";
?>
<style>
    .about-us {
    width: 100%;
    padding: 70px 0;
}

.about-us img {
    height: auto;
    width: 370px;
    filter: drop-shadow(0 0 20px rgba(161, 182, 253, 0.825));
}

.about-us-text {
    width: 800px;
}

.about-us-main {
    margin: 0 90px;
    display: flex;
    align-items: center;
    justify-content: space-around;
}

.about-us h1 {
    text-align: center;
    font-size: 50px;
    margin-bottom: 35px;
}

.about-us h5 {
    text-align: center;
    font-size: 35px;
    margin-top: 35px;
    letter-spacing: 5px;
}

.about-us-text p {
    letter-spacing: 1px;
    line-height: 35px;
    font-size: 18px;
    text-align: justify;
    margin-bottom: 35px;
}

</style>
<div class="about-us">
    <h1>About Us</h1>
    <div class="about-us-main">
        <img src="Photos/about_us_image1.png" alt="">
        <div class="about-us-text">
            <p>Welcome to BattleZoneHub, your ultimate destination for immersive gaming experiences. We are dedicated to providing gamers with the best community-driven platform, offering a wide range of games, tournaments, and events.</p>
        </div>
    </div>
    <div class="about-us-main">
        <div class="about-us-text">
            <p>Our goal is to make your gaming experience as smooth as possible. With instant digital delivery, secure
                transactions, and a user-friendly interface, purchasing game codes from BattleZoneHub is quick, easy, and
                reliable. We source all of our codes from trusted suppliers to ensure you get authentic and fully
                activated game keys.</p>
        </div>
        <img src="Photos/about_us_image2.png" alt="">
    </div>
    <div class="about-us-main">
        <img src="Photos/about_us_image3.png" alt="">
        <div class="about-us-text">
            <p>Thank you for choosing BattleZoneHub as your one-stop destination for PC game keys. Start your next adventure
                today, and let the games begin.</p>
        </div>
    </div>
    <h5>HAPPY GAMING!!</h5>
</div>

<?php
include "Footer.php";
?>