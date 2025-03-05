<?php
include "Header.php";
require_once("connection.php");

function deletePreviousStreamsByCategory($conn, $category) {
    $sql = "DELETE FROM youtube_lives WHERE LOWER(category) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->close();
}

// Function to add a new stream
function addNewStream($conn, $video_id, $title, $description, $thumbnail_url, $category) {
    // Delete previous streams for the same category
    deletePreviousStreamsByCategory($conn, $category);

    // Insert the new stream
    $sql = "INSERT INTO youtube_lives (video_id, title, description, thumbnail_url, category, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $video_id, $title, $description, $thumbnail_url, $category);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

// Get selected category from URL parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$watch = isset($_GET['watch']) ? $_GET['watch'] : false;

// Only fetch stream for the selected category
$stream = null;
if ($category) {
    $sql = "SELECT * FROM youtube_lives WHERE LOWER(category) = LOWER(?) ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $stream = $result->fetch_assoc();
    $stmt->close();
}

// Handle new stream submission
if (isset($_POST['add_stream'])) {
    $video_id = $_POST['video_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $thumbnail_url = $_POST['thumbnail_url'];
    $stream_category = $_POST['category'];
    
    if (addNewStream($conn, $video_id, $title, $description, $thumbnail_url, $stream_category)) {
        // Redirect to the category page after adding a stream
        header("Location: " . $_SERVER['PHP_SELF'] . "?category=" . urlencode($stream_category));
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Live Streams</title>
    <link rel="stylesheet" href="./CSS/sharelive.css">
</head>
<body>
<div class="container">
    <?php
    if ($category && $stream) {
        if ($watch) {
            ?>
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
            <div class="youtube-link">
                <p>You can visit YouTube for live chat and watch previous tournaments</p>
                <a href="https://www.youtube.com/channel/UCr14rNcua5zkxf2TUF0cCUA" target="_blank" class="visit-btn">Visit Now</a>
            </div>
            <?php
        } else {
            ?>
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
                        <a href="?category=<?php echo urlencode($category); ?>&watch=true" class="watch-btn">Watch Now</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <h2>Select a Category</h2>
        <div class="category-buttons">
            <a href="?category=PUBG" class="category-btn">PUBG</a>
            <a href="?category=Free Fire" class="category-btn">Free Fire</a>
        </div>
        <?php
    }
    ?>

    
</div>
</body>
</html>

<?php include "Footer.php"; ?>