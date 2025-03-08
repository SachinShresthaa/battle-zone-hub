<?php
include "Header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/viewDetails.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <div class="nav-container">
        <button class="nav-item active" data-tab="registered">Registered team</button>
        <button class="nav-item" data-tab="price">Price Pool</button>
        <button class="nav-item" data-tab="rules">Rules</button>
    </div>

    <div class="content-container">
        <div class="content-section active" id="registered">
            <h2>Registered Teams</h2>
            <?php
                include "registeredTeam.php";
            ?>
        </div>
        
        <div class="content-section" id="price">
            <h2>Price Pool</h2>
            <?php
                include "pricepool.php";
            ?>
        </div>
        
<div class="content-section" id="rules">
    <h2>Rules</h2>
    <?php
    include "gameRules.php";
    ?>
</div>
    </div>

    <script>
        const navItems = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.content-section');

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                navItems.forEach(nav => nav.classList.remove('active'));
                contentSections.forEach(section => section.classList.remove('active'));
                
                item.classList.add('active');
                
                const tabId = item.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>