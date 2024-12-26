<?php
// Include database connection
include_once '../connection.php';

// Initialize variables
$editTournament = null; // To prevent undefined variable error
$actionMessage = "";

// Fetch tournament details for editing
if (isset($_GET['edit'])) {
    $tournamentId = intval($_GET['edit']);
    $query = "SELECT * FROM tournaments WHERE id = $tournamentId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $editTournament = $result->fetch_assoc();
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $thumbnailPath = "";

    // Handle file upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $thumbnailPath = 'uploads/' . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath);
    }

    if ($action === 'add') {
        $query = "INSERT INTO tournaments (name, category, date, time, thumbnail) VALUES ('$name', '$category', '$date', '$time', '$thumbnailPath')";
    } elseif ($action === 'update' && isset($_POST['tournament_id'])) {
        $tournamentId = intval($_POST['tournament_id']);
        $query = "UPDATE tournaments SET name='$name', category='$category', date='$date', time='$time'";
        if ($thumbnailPath) {
            $query .= ", thumbnail='$thumbnailPath'";
        }
        $query .= " WHERE id=$tournamentId";
    } elseif ($action === 'delete' && isset($_POST['tournament_id'])) {
        $tournamentId = intval($_POST['tournament_id']);
        $query = "DELETE FROM tournaments WHERE id=$tournamentId";
    }

    if ($conn->query($query)) {
        $actionMessage = "Action completed successfully.";
    } else {
        $actionMessage = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Management</title>
    <style>
        .preview-image {
            max-width: 100px;
            max-height: 100px;
            margin: 10px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .current-files {
            margin: 10px 0;
            font-size: 0.9em;
            color: #666;
        }
        .message {
            margin: 10px 0;
            color: green;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="content">
            <?php if ($actionMessage): ?>
                <div class="message"><?php echo $actionMessage; ?></div>
            <?php endif; ?>

            <h2><?php echo $editTournament ? 'Edit Tournament' : 'Add Tournament'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="add-tournament">
                <input type="hidden" name="action" value="<?php echo $editTournament ? 'update' : 'add'; ?>">
                <?php if ($editTournament): ?>
                    <input type="hidden" name="tournament_id" value="<?php echo $editTournament['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Tournament Name:</label>
                    <input type="text" name="name" value="<?php echo $editTournament ? $editTournament['name'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Category:</label>
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <option value="PUBG" <?php echo ($editTournament && $editTournament['category'] === 'PUBG') ? 'selected' : ''; ?>>PUBG</option>
                        <option value="FreeFire" <?php echo ($editTournament && $editTournament['category'] === 'FreeFire') ? 'selected' : ''; ?>>FreeFire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" name="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $editTournament ? $editTournament['date'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Time:</label>
                    <input type="time" name="time" value="<?php echo $editTournament ? $editTournament['time'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Thumbnail:</label>
                    <?php if ($editTournament): ?>
                        <div class="current-files">
                            Current: <img src="<?php echo $editTournament['thumbnail']; ?>" class="preview-image">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" accept="image/*" <?php echo $editTournament ? '' : 'required'; ?>>
                </div>

                <button type="submit"><?php echo $editTournament ? 'Update' : 'Add'; ?> Tournament</button>
                <?php if ($editTournament): ?>
                    <a href="?" class="button">Cancel Edit</a>
                <?php endif; ?>
            </form>

            <h2>Tournament List</h2>
            <table class="tournament-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Thumbnail</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM tournaments ORDER BY date DESC, time DESC";
                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                            <td><img src='{$row['thumbnail']}' alt='Thumbnail' class='preview-image'></td>
                            <td>
                                <a href='?edit={$row['id']}' class='button'>Edit</a>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='tournament_id' value='{$row['id']}'>
                                    <button type='submit' onclick='return confirm(\"Are you sure you want to delete this tournament?\");'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
