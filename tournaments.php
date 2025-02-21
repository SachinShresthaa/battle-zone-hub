
<?php 

include "headerWithprofile.php";

// Example: After fetching tournament details from the database
$entryFee = isset($row['price']) ? htmlspecialchars($row['price']) : 'N/A';

// Save the entry fee in the session
$_SESSION['entry_fee'] = $entryFee;
?>
 
<!-- <?php include "Header.php"; ?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments</title>
    <link rel="stylesheet" href="css/tournaments.css">
 
</head>
<body>
    <div class="Main">
        <?php
        // Get the game category from URL parameter
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        
        // Set the title based on category
        $title = $category ? ucfirst($category) . ' Tournament' : 'All Tournaments';
        ?>
        
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <div class="line"></div>
        
        <div class="Body">
        <div class="Body">
    <?php
    // Include database connection
    include_once 'connection.php';

    // Function to validate image path
    function validateImagePath($path) {
        $path = str_replace('../', '', $path);
        if (empty($path) || !file_exists($path)) {
            return 'uploads/default-tournament-image.jpg';
        }
        return $path;
    }

    // Prepare the query based on category, showing only one tournament
    if ($category) {
        $category = $conn->real_escape_string($category);
        $query = "SELECT * FROM tournaments WHERE LOWER(category) = LOWER('$category') ORDER BY date DESC, time DESC LIMIT 1";
    } else {
        $query = "SELECT * FROM tournaments ORDER BY date ASC, time ASC LIMIT 1";
    }

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagePath = validateImagePath($row['thumbnail']);
            $entryFee = isset($row['price']) ? htmlspecialchars($row['price']) : 'N/A';  // Default to 'N/A' if no entry fee is set

            echo "
            <div class='box'>
                <div class='leftSide'>
                    <img src='" . htmlspecialchars($imagePath) . "' alt='Tournament Thumbnail' onerror=\"this.onerror=null;this.src='uploads/default-tournament-image.jpg';\">
                </div>
                <div class='rightSide'>
                    <div class='tournament-title'>" . htmlspecialchars($row['name']) . "</div>
                    <div class='tournament-details'>Category: " . htmlspecialchars($row['category']) . "</div>
                    <div class='tournament-details'>Date: " . date("F j, Y", strtotime($row['date'])) . "</div>
                    <div class='tournament-details'>Time: " . date("g:i A", strtotime($row['time'])) . "</div>
                    <div class='tournament-details'>Registration Deadline: " . date("F j, Y", strtotime($row['registration_deadline'])) . "</div>
                    <div class='tournament-details'>Entry Fee: Rs " . $entryFee . "</div>
                    <div class='button-container'>";

            // Handle registration button logic
            if (strtolower($row['category']) == 'pubg') {
                $registerLink = 'teamregisterpubg.php?tournament_id=' . $row['id'];
            } elseif (strtolower($row['category']) == 'freefire') {
                $registerLink = 'teamregisterff.php?tournament_id=' . $row['id'];
            } else {
                $registerLink = '#';
            }

            echo "
                <a href='" . $registerLink . "'>
                    <button class='register-btn'>Register Now</button>
                </a>
                <a href='viewDetails.php?id=" . $row['id'] . "'>
    <button class='view-btn'>View Details</button>
</a>
            </div>
        </div>
    </div>";
        }
    } else {
        echo "<p>No tournaments found for this category.</p>";
    }
    ?>
    <div class="space"></div>
</div>
</div>
    </div>
</body>
</html>
<?php include "Footer.php"; ?>