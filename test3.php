<?php
// Optionally, you can set default file to include
$fileToInclude = isset($_GET['file']) ? $_GET['file'] : 'file1.php'; // Default to file1.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leaderboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #575757;
            padding: 5px;
        }

        .content {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="?file=test1.php">freefire</a>
        <a href="?file=file2.php">File 2</a>
    </div>

    <div class="content">
        <h2>Manage Leaderboard</h2>
        
        <!-- Dynamically include the selected file -->
        <?php
        if (file_exists($fileToInclude)) {
            include $fileToInclude;
        } else {
            echo "<p>File not found.</p>";
        }
        ?>
    </div>

</body>
</html>
