<?php
    include "Header.php"
?>

<?php
require_once("connection.php");
// Function to delete all previous streams
function deleteAllPreviousStreams($conn) {
    $sql = "DELETE FROM youtube_lives";
    return $conn->query($sql);
}

// Function to add new stream
function addNewStream($conn, $video_id, $title, $description, $thumbnail_url) {
    // First delete all previous streams
    deleteAllPreviousStreams($conn);
    
    // Then insert the new stream
    $sql = "INSERT INTO youtube_lives (video_id, title, description, thumbnail_url, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $video_id, $title, $description, $thumbnail_url);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}
// Main stream display logic
if (isset($_GET['id'])) {
    $video_id = $_GET['id'];
    
    // Use prepared statement for security
    $sql = "SELECT * FROM youtube_lives WHERE video_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stream = $result->fetch_assoc();
    $stmt->close();
    
    if (!$stream) {
        header("Location: ?"); // Redirect to main dashboard if video doesn't exist
        exit();
    }
} else {
    // Fetch the single live stream
    $sql = "SELECT * FROM youtube_lives ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
}

// Admin section for adding new stream (should be in admin panel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_stream'])) {
    $new_video_id = $_POST['video_id'];
    $new_title = $_POST['title'];
    $new_description = $_POST['description'];
    $new_thumbnail = $_POST['thumbnail_url'];
    
    if (addNewStream($conn, $new_video_id, $new_title, $new_description, $new_thumbnail)) {
        header("Location: ?"); // Redirect to main page after successful addition
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($stream) ? htmlspecialchars($stream['title']) : "User Dashboard - Live Streams"; ?></title>
    <link rel="stylesheet" href="./CSS/sharelive.css">
</head>
<body>
    <div class="container">
        <?php if (isset($stream)): ?>
            <!-- Single Video View -->
            <div class="video-container">
                <iframe 
                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($stream['video_id']); ?>?autoplay=1" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <div class="stream-info">
                <h1><?php echo htmlspecialchars($stream['title']); ?></h1>
                <p><?php echo htmlspecialchars($stream['description']); ?></p>
            </div>
        <?php else: ?>

            <h2>Available Live Streams</h2>
            <div class="stream-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="stream-card">
                            <!-- Correct Thumbnail Path -->
                            <img src="admin/<?php echo htmlspecialchars($row['thumbnail_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                 class="thumbnail"
                                 onerror="this.src='default-thumbnail.jpg';">
                            <div class="noflex">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <div class="description">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </div>
                                <div class="move-button">
                                    <a href="?id=<?php echo htmlspecialchars($row['video_id']); ?>" 
                                    class="watch-btn">Watch Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No live streams available at the moment.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="youtube-link">
            <p>You can visit YouTube for live chat and watch previous tournaments</p>
            <a href="https://www.youtube.com/channel/UCr14rNcua5zkxf2TUF0cCUA" target="_blank" class="visit-btn" >Visit Now
            </a>
        </div>
    </div>
</body>
</html>
<?php
     include "Footer.php"
?>