<?php
// Database connection
require_once("../connection.php");

$error_message = $success_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['youtube_url'])) {
        $youtube_url = $conn->real_escape_string($_POST['youtube_url']);
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);

        $video_id = '';

        // Updated regex pattern to match various YouTube URL formats
        if (preg_match('/(?:youtube\.com\/(?:(?:v|e(?:mbed|live)?)\/|(?:[^\/]+\/.+\/|.*[?&]v=|live\/))([a-zA-Z0-9_-]{11})|youtu\.be\/([a-zA-Z0-9_-]{11}))/i', $youtube_url, $match)) {
            $video_id = $match[1] ?: $match[2];  // Get the video_id from the first or second match group
        } else {
            $error_message = "Invalid YouTube URL.";  
        }

        // Check if a thumbnail is uploaded
        $thumbnail_url = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . uniqid() . "_" . basename($_FILES["thumbnail"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["thumbnail"]["tmp_name"]);

            if ($check !== false && $_FILES["thumbnail"]["size"] <= 2 * 1024 * 1024 && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                    $thumbnail_url = $target_file;
                } else {
                    $error_message = "Error uploading the thumbnail.";
                }
            } else {
                $error_message = "Invalid thumbnail file. Only JPG, JPEG, PNG, and GIF under 2MB are allowed.";
            }
        }

        if (!$error_message) {
            // Check if video_id already exists
            $check_sql = "SELECT * FROM youtube_lives WHERE video_id = '$video_id'";
            $check_result = $conn->query($check_sql);

            if ($check_result && $check_result->num_rows > 0) {
                $error_message = "A video with this YouTube ID already exists.";
            } else {
                // Insert into the database
                $sql = "INSERT INTO youtube_lives (video_id, title, description, thumbnail_url) 
                        VALUES ('$video_id', '$title', '$description', '$thumbnail_url')";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Live stream added successfully!";
                } else {
                    $error_message = "Database error: " . $conn->error;
                }
            }
        }
    } elseif (isset($_POST['tournament_id'])) { // Delete action
        $tournamentId = intval($_POST['tournament_id']);
        $query = "DELETE FROM youtube_lives WHERE id = $tournamentId";

        if ($conn->query($query) === TRUE) {
            $success_message = "Live stream deleted successfully!";
        } else {
            $error_message = "Error deleting the live stream: " . $conn->error;
        }
    }
}

// Fetch all live streams
$sql = "SELECT * FROM youtube_lives ORDER BY created_at DESC";
$result = $conn->query($sql);

$videos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage YouTube Live Streams</title>
    <link href="./CSS/sharelive.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Share YouTube Live Streams</h2>

        <?php if ($success_message): ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="youtube_url">YouTube URL:</label>
                <input type="text" name="youtube_url" id="youtube_url" placeholder="Enter YouTube video URL" required>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" placeholder="Enter stream title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="4" placeholder="Enter stream description" required></textarea>
            </div>
            <div class="form-group">
                <label for="thumbnail">Thumbnail:</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
            </div>
            <button type="submit" class="btn">Share Live Stream</button>
        </form>

        <h2>Video List</h2>
        <?php if (!empty($videos)): ?>
    <table style="margin-bottom: 50px;">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $video): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>" width="120"></td>
                            <td><?php echo htmlspecialchars($video['title']); ?></td>
                            <td><?php echo htmlspecialchars($video['description']); ?></td>
                            <td>
                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this stream?');">
                                    <input type="hidden" name="tournament_id" value="<?php echo $video['id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No live streams available.</p>
        <?php endif; ?>
        
    <div class="nothing"></div>
    </div>
</body>
</html>