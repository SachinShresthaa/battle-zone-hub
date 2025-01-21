<?php include "Header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches</title>
    <link rel="stylesheet" href="css/matches.css">
    <script>
        function handleMatchClick(matchId) {
            alert("Clicked on Match ID: " + matchId);
        }
        
        function handleJoinClick(matchId, event) {
            event.stopPropagation();
            alert("Joined Match ID: " + matchId);
        }
        
        function handleDetailsClick(matchId, event) {
            event.stopPropagation();
            alert("Viewing details for Match ID: " + matchId);
        }
    </script>
    <style>
        .leftSide {
            width: 200px;
            height: 200px;
            min-width: 200px;
            overflow: hidden;
            border-radius: 8px;
            margin-right: 20px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leftSide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .box:hover .leftSide img {
            transform: scale(1.05);
        }

        .match-status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-top: 5px;
        }

        .status-upcoming {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-live {
            background-color: #e8f5e9;
            color: #2e7d32;
            animation: pulse 2s infinite;
        }

        .status-completed {
            background-color: #f5f5f5;
            color: #616161;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="Main">
        <?php
        // Get the game category from URL parameter
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        
        // Set the title based on category
        $title = $category ? ucfirst($category) . ' Matches' : 'All Matches';
        ?>
        
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <div class="line"></div>
        
        <div class="Body">
            <?php
            // Include database connection
            include_once 'connection.php';
            
            function validateImagePath($path) {
                $path = str_replace('../', '', $path);
                if (empty($path) || !file_exists($path)) {
                    return 'uploads/default-match-image.jpg';
                }
                return $path;
            }

            // Get current timestamp for comparing match status
            $currentTime = date('Y-m-d H:i:s');

            // Prepare the query based on category
            $query = "SELECT * FROM matches";
            if ($category) {
                $category = $conn->real_escape_string($category);
                $query .= " WHERE LOWER(category) = LOWER('$category')";
            }
            $query .= " ORDER BY match_date ASC, match_time ASC";
            
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Validate and clean up image path
                    $imagePath = validateImagePath($row['match_thumbnail']);
                    
                    // Calculate match status
                    $matchDateTime = $row['match_date'] . ' ' . $row['match_time'];
                    $matchEndTime = date('Y-m-d H:i:s', strtotime($matchDateTime . ' +2 hours')); // Assuming 2-hour matches
                    
                    if (strtotime($currentTime) < strtotime($matchDateTime)) {
                        $status = 'upcoming';
                        $statusText = 'Upcoming';
                    } elseif (strtotime($currentTime) > strtotime($matchEndTime)) {
                        $status = 'completed';
                        $statusText = 'Completed';
                    } else {
                        $status = 'live';
                        $statusText = 'LIVE';
                    }
                    
                    echo "
                    <div class='box' onclick='handleMatchClick({$row['id']})'>
                        <div class='leftSide'>
                            <img src='" . htmlspecialchars($imagePath) . "' 
                                 alt='Match Thumbnail'
                                 onerror=\"this.src='uploads/default-match-image.jpg'\">
                        </div>
                        <div class='rightSide'>
                            <div class='match-title'>" . htmlspecialchars($row['match_name']) . "</div>
                            <div class='match-details'>Category: " . htmlspecialchars($row['category']) . "</div>
                            <div class='match-details'>Date: " . date("F j, Y", strtotime($row['match_date'])) . "</div>
                            <div class='match-details'>Time: " . date("g:i A", strtotime($row['match_time'])) . "</div>
                            <div class='match-details'>Prize Pool: $" . number_format($row['prize_pool'], 2) . "</div>
                            <div class='match-status status-{$status}'>{$statusText}</div>
                            <div class='button-container'>";

                    if ($status === 'upcoming') {
                        if (strtolower($row['category']) == 'pubg') {
                            echo "<a href='joinmatchpubg.php?match_id={$row['id']}'>
                                    <button class='join-btn'>Join Match</button>
                                  </a>";
                        } elseif (strtolower($row['category']) == 'freefire') {
                            echo "<a href='joinmatchff.php?match_id={$row['id']}'>
                                    <button class='join-btn'>Join Match</button>
                                  </a>";
                        } else {
                            echo "<button class='join-btn' onclick='handleJoinClick({$row['id']}, event)'>Join Match</button>";
                        }
                    } elseif ($status === 'live') {
                        echo "<button class='watch-btn' onclick='window.location.href=\"watch.php?match_id={$row['id']}\"'>Watch Live</button>";
                    }

                    echo "
                                <button class='view-btn' onclick='handleDetailsClick({$row['id']}, event)'>View Details</button>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No " . ($category ? htmlspecialchars($category) . " " : "") . "matches are currently available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php include "Footer.php"; ?>