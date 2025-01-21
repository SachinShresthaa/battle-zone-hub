<?php include "Header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments</title>
    <link rel="stylesheet" href="css/tournaments.css">
    <!-- <script>
        function handleBoxClick(tournamentId) {
            alert("Clicked on Tournament ID: " + tournamentId);
        }
        
        function handleRegisterClick(tournamentId, event) {
            event.stopPropagation();
            alert("Registered for Tournament ID: " + tournamentId);
        }
        
        function handleViewDetailsClick(tournamentId, event) {
            event.stopPropagation();
            alert("Viewing details for Tournament ID: " + tournamentId);
        }
    </script> -->
    <style>
        /* Left side styling with image */
        .leftSide {
            width: 200px;
            height: 200px;
            min-width: 200px;  /* Prevents shrinking on small screens */
            overflow: hidden;
            border-radius: 8px;
            margin-right: 20px;
            background-color: #f5f5f5;  /* Light background for empty states */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leftSide img {
            width: 100%;
            height: 100%;
            object-fit: cover;  /* Maintains aspect ratio while filling container */
            transition: transform 0.3s ease;  /* Smooth zoom effect on hover */
        }

        /* Optional hover effect */
        .box:hover .leftSide img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="Main">
        <?php
        // Get the game category from URL parameter
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        
        // Set the title based on category
        $title = $category ? ucfirst($category) . ' Tournaments' : 'All Tournaments';
        ?>
        
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <div class="line"></div>
        
        <div class="Body">
            <?php
            // Include database connection
            include_once 'connection.php';
            
            // Function to validate image path
            function validateImagePath($path) {
                // Remove '../' from the path as it's stored relative to admin directory
                $path = str_replace('../', '', $path);
                
                // Check if file exists
                if (empty($path) || !file_exists($path)) {
                    return 'uploads/default-tournament-image.jpg';
                }
                return $path;
            }

            // Prepare the query based on category
            $query = "SELECT * FROM tournaments";
            if ($category) {
                $category = $conn->real_escape_string($category);
                $query .= " WHERE LOWER(category) = LOWER('$category')";
            }
            $query .= " ORDER BY date ASC, time ASC";
            
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Validate and clean up image path
                    $imagePath = validateImagePath($row['thumbnail']);
                    
                    // Check if registration deadline has passed
                    $isDeadlinePassed = strtotime($row['registration_deadline']) < strtotime('today');
                    
                    echo "
                    <div class='box" . ($isDeadlinePassed ? ' deadline-passed' : '') . "' onclick='handleBoxClick({$row['id']})'>
                        <div class='leftSide'>
                            <img src='" . htmlspecialchars($imagePath) . "' 
                                 alt='Tournament Thumbnail'
                                 onerror=\"this.src='uploads/default-tournament-image.jpg'\">
                        </div>
                        <div class='rightSide'>
                            <div class='tournament-title'>" . htmlspecialchars($row['name']) . "</div>
                            <div class='tournament-details'>Category: " . htmlspecialchars($row['category']) . "</div>
                            <div class='tournament-details'>Date: " . date("F j, Y", strtotime($row['date'])) . "</div>
                            <div class='tournament-details'>Time: " . date("g:i A", strtotime($row['time'])) . "</div>
                            <div class='tournament-details'>Registration Deadline: " . date("F j, Y", strtotime($row['registration_deadline'])) . 
                            ($isDeadlinePassed ? ' <span class="deadline-notice">(Registration Closed)</span>' : '') . "</div>
                            <div class='button-container'>";

                            // If the registration deadline has passed, remove the Register Now button
                            if ($isDeadlinePassed) {
                                echo "<button class='register-btn' disabled>Registration Closed</button>";
                            } else {
                                // Check if the category is PUBG or FreeFire
                                if (strtolower($row['category']) == 'pubg') {
                                    echo "
                                    <a href='teamregisterpubg.php?tournament_id={$row['id']}'>
                                        <button class='register-btn'>Register Now</button>
                                    </a>
                                    ";
                                } elseif (strtolower($row['category']) == 'freefire') {
                                    echo "
                                    <a href='teamregisterff.php?tournament_id={$row['id']}'>
                                        <button class='register-btn'>Register Now</button>
                                    </a>
                                    ";
                                } else {
                                    echo "<button class='register-btn' onclick='handleRegisterClick({$row['id']}, event)'>Register Now</button>";
                                }
                            }

                            echo "
                                <button class='view-btn' onclick='handleViewDetailsClick({$row['id']}, event)'>View Details</button>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p>No " . ($category ? htmlspecialchars($category) . " " : "") . "tournaments are currently available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php include "Footer.php"; ?>
