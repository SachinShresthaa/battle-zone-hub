<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments</title>
    <style>
        /* Previous CSS styles remain the same */
    </style>
    <script>
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
    </script>
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

            // Prepare the query based on category
            $query = "SELECT * FROM tournaments";
            if ($category) {
                $category = $conn->real_escape_string($category);
                $query .= " WHERE LOWER(category) = LOWER('$category')";
            }
            $query .= " ORDER BY date ASC, time ASC";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='box' onclick='handleBoxClick({$row['id']})'>
                        <div class='leftSide'>
                            <img src='" . htmlspecialchars($row['thumbnail']) . "' alt='Tournament Thumbnail'>
                        </div>
                        <div class='rightSide'>
                            <div class='tournament-title'>" . htmlspecialchars($row['name']) . "</div>
                            <div class='tournament-details'>Category: " . htmlspecialchars($row['category']) . "</div>
                            <div class='tournament-details'>Date: " . date("F j, Y", strtotime($row['date'])) . "</div>
                            <div class='tournament-details'>Time: " . date("g:i A", strtotime($row['time'])) . "</div>
                            <div class='tournament-details'>Registration Deadline: " . date("F j, Y", strtotime($row['registration_deadline'])) . "</div>
                            <div class='button-container'>
                                <button class='register-btn' onclick='handleRegisterClick({$row['id']}, event)'>Register Now</button>
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