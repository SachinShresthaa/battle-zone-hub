<?php
    include "Header.php";
    require_once("connection.php");

    // Function to delete all previous streams
    function deleteAllPreviousStreams($conn) {
        $sql = "DELETE FROM youtube_lives";
        return $conn->query($sql);
    }

    // Fetch live streams for the selected category
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $streams = [];
    if (!empty($category)) {
        $sql = "SELECT * FROM youtube_lives WHERE LOWER(category) = LOWER(?) ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $streams[] = $row;
        }
        $stmt->close();
    }

    // Function to add a new stream
    function addNewStream($conn, $video_id, $title, $description, $thumbnail_url, $category) {
        // First delete all previous streams
        deleteAllPreviousStreams($conn);

        // Then insert the new stream
        $sql = "INSERT INTO youtube_lives (video_id, title, description, thumbnail_url, category, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $video_id, $title, $description, $thumbnail_url, $category);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Admin section for adding a new stream
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_stream'])) {
        $new_video_id = $_POST['video_id'];
        $new_title = $_POST['title'];
        $new_description = $_POST['description'];
        $new_thumbnail = $_POST['thumbnail_url'];
        $new_category = $_POST['category'];

        if (addNewStream($conn, $new_video_id, $new_title, $new_description, $new_thumbnail, $new_category)) {
            header("Location: ?category=$new_category"); // Redirect to the selected category after successful addition
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo !empty($category) ? htmlspecialchars($category) . " Live Streams" : "Live Streams"; ?></title>
    <link rel="stylesheet" href="./CSS/sharelive.css">
</head>
<body>
<div class="container">
    <h2>Live Streams</h2>
    <div class="category-buttons">
        <a href="?category=PUBG" class="category-btn">PUBG Live Streams</a>
        <a href="?category=FreeFire" class="category-btn">FreeFire Live Streams</a>
    </div>
    <div class="stream-grid">
        <?php if (!empty($streams)): ?>
            <?php foreach ($streams as $stream): ?>
                <div class="stream-card">
                    <img src="admin/<?php echo htmlspecialchars($stream['thumbnail_url']); ?>" 
                         alt="<?php echo htmlspecialchars($stream['title']); ?>" 
                         class="thumbnail"
                         onerror="this.src='default-thumbnail.jpg';">
                    <div class="noflex">
                        <h3><?php echo htmlspecialchars($stream['title']); ?></h3>
                        <div class="description">
                            <?php echo htmlspecialchars($stream['description']); ?>
                        </div>
                        <div class="move-button">
                            <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($stream['video_id']); ?>" 
                               target="_blank" class="watch-btn">Watch Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No live streams available for <?php echo htmlspecialchars($category); ?> at the moment.</p>
        <?php endif; ?>
    </div>
    <div class="youtube-link">
        <p>You can visit YouTube for live chat and watch previous tournaments</p>
        <a href="https://www.youtube.com/channel/UCr14rNcua5zkxf2TUF0cCUA" target="_blank" class="visit-btn">Visit Now</a>
    </div>
</div>
</body>
</html>
<?php include "Footer.php"; ?>
