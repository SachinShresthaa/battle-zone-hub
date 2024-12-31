<?php
require_once("connection.php");

if (isset($_GET['id'])) {

    $video_id = $_GET['id'];
    $sql = "SELECT * FROM youtube_lives WHERE video_id = '" . $conn->real_escape_string($video_id) . "'";
    $result = $conn->query($sql);
    $stream = $result->fetch_assoc();

    if (!$stream) {
        header("Location: ?"); // Redirect to the main dashboard if the video doesn't exist
        exit();
    }
} else {
    // Fetch all live streams
    $sql = "SELECT * FROM youtube_lives ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>
<?php
            include "Header.php"
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
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="description">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </div>
                            <a href="?id=<?php echo htmlspecialchars($row['video_id']); ?>" 
                               class="watch-btn">Watch Now</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No live streams available at the moment.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
            include "Footer.php"
?>