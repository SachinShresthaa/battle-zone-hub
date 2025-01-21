<?php
include_once '../connection.php';

$editTournament = null;
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
    $thumbnailPath = "";

    if ($action === 'add' || $action === 'update') {
        // Handle file upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnailPath = '../uploads/' . basename($_FILES['thumbnail']['name']);
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath);
        }

        $name = $conn->real_escape_string($_POST['name']);
        $category = $conn->real_escape_string($_POST['category']);
        $date = $conn->real_escape_string($_POST['date']);
        $time = $conn->real_escape_string($_POST['time']);
        $registrationDeadline = $conn->real_escape_string($_POST['registration_deadline']);

        if ($action === 'add') {
            $query = "INSERT INTO tournaments (name, category, date, time, registration_deadline, thumbnail) 
                      VALUES ('$name', '$category', '$date', '$time', '$registrationDeadline', '$thumbnailPath')";
        } elseif ($action === 'update' && isset($_POST['tournament_id'])) {
            $tournamentId = intval($_POST['tournament_id']);
            $query = "UPDATE tournaments 
                      SET name='$name', category='$category', date='$date', time='$time', registration_deadline='$registrationDeadline'";
            if ($thumbnailPath) {
                $query .= ", thumbnail='$thumbnailPath'";
            }
            $query .= " WHERE id=$tournamentId";
        }
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
    <link href="./css/tournament.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="upper-form">
            <h2><?php echo $editTournament ? 'Edit Tournament' : 'Add Tournament'; ?></h2>
            <?php if ($actionMessage): ?>
                <div class="message"><?php echo $actionMessage; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
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
                    <label>Registration Deadline:</label>
                    <input type="date" name="registration_deadline" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $editTournament ? $editTournament['registration_deadline'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Thumbnail:</label>
                    <?php if ($editTournament): ?>
                        <div class="current-files">
                            Current: <img src="<?php echo $editTournament['thumbnail']; ?>" alt="Thumbnail">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" accept="image/*" <?php echo $editTournament ? '' : 'required'; ?>>
                </div>
                <button type="submit" class="btn"><?php echo $editTournament ? 'Update' : 'Add'; ?> Tournament</button>
                <?php if ($editTournament): ?>
                    <a href="?" class="btn">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>
        <div class="fetch-data">
            <h2>Tournament List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Registration Deadline</th>
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
                            <td>{$row['registration_deadline']}</td>
                            <td><img src='{$row['thumbnail']}' alt='Thumbnail' class='current-files'></td>
                            <td>
                                <a href='?edit={$row['id']}' class='btn'>Edit</a>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='tournament_id' value='{$row['id']}'>
                                    <button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure?\");'>Delete</button>
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
