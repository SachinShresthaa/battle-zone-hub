<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Teams</title>
  <style>

    .sub-nav {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 20px;
    }
    .sub-button {
      position: relative;
      padding: 12px 20px;
      cursor: pointer;
      background: linear-gradient(45deg, #2e2e2e, #3c3c3c);
      border: none;
      color: #fff;
      font-weight: 600;
      font-size: 18px;
      border-radius: 2px;
      transition: all 0.3s ease;
    }
    .sub-button:hover {
      transform: translateY(-3px);
      background:#d4d2d2;
      color: #000;
    }
    .sub-button.active {
      background:#d4d2d2;
      color: #000;
      transform: none;
    }

    .sub-content {
      display: none;
      margin-top: 20px;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    .sub-content.active {
      display: block;
    }
    .sub-content h3 {
      margin-bottom: 15px;
      font-size: 20px;
      padding-bottom: 8px;
      font-weight: 500;
    }

  </style>
</head>
<body>

  <div class="container">

    <!-- Sub-Nav Buttons -->
    <div class="sub-nav">
      <button class="sub-button active" data-subtab="freeFireContent">
        Manage Free Fire Teams
      </button>
      <button class="sub-button" data-subtab="pubgContent">
        Manage Pubg Teams
      </button>
    </div>

    <!-- Sub-Content 1: Free Fire Teams -->
    <div id="freeFireContent" class="sub-content active">
      <?php
      include "manage_tournaments/manageFreeFireTeam.php"
      ?>
    </div>

    <!-- Sub-Content 2: Pubg Teams -->
    <div id="pubgContent" class="sub-content">
      <?php
      include "manage_tournaments/managePubgTeam.php"
      ?>
    </div>
  </div>

  <script>
    // Grab all sub-buttons and sub-contents
    const subButtons = document.querySelectorAll('.sub-button');
    const subContents = document.querySelectorAll('.sub-content');

    // Add click listeners to each sub-button
    subButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Remove 'active' from all sub-buttons and sub-contents
        subButtons.forEach(btn => btn.classList.remove('active'));
        subContents.forEach(content => content.classList.remove('active'));

        // Add 'active' to the clicked button and its matching content
        button.classList.add('active');
        const targetId = button.getAttribute('data-subtab');
        document.getElementById(targetId).classList.add('active');
      });
    });
  </script>

</body>
</html>
