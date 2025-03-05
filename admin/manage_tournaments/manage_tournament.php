
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="viewDetails.css" rel="stylesheet">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family:Jacques Francois ;
}

body {
    background: black;
    min-height: 100vh;
    color: #fff;
}

.nav-container {
    width: 100%;
    background: black;
    backdrop-filter: blur(10px);
    display: flex;
    justify-content: center;
    position: sticky;
    top: 0;
    box-shadow: black;
}

.nav-item {
    padding: 15px 30px;
    color: #fff;
    cursor: pointer;
    border: none;
    background: none;
    font-size: 25px;
    position: relative;
    margin: 0 10px;
    transition: all 0.3s ease;
    font-weight:bold;
}

.nav-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background:#d4d2d2;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}


.nav-item.active {
    color: #4ecdc4;
    transform: translateY(-2px);
}

.nav-item.active::before {
    transform: scaleX(1);
    height: 4px;
}

.content-section {
    display: none;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.content-section.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}
.content-section {
    position: relative;
    overflow: hidden;
}

    </style>
</head>
<body>
    <div class="nav-container">
        <button class="nav-item active" data-tab="registered">Add Tournaments</button>
        <button class="nav-item" data-tab="price">Manage Current Tournaments</button>
    </div>

    <div class="content-container">
        <div class="content-section active" id="registered">
        <?php
                include "add_tournament.php";
            ?>
        </div>
        
        <div class="content-section" id="price">
        <?php
                include "manage_current_tournament.php";
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