<?php
include_once '../../connection.php';

$editTournament = null;
$actionMessage = "";

// Fetch tournament details for editing
if (isset($_GET['edit'])) {
    $tournamentId = intval($_GET['edit']);
    $query = "SELECT * FROM tournaments WHERE id = $tournamentId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $editTournament = $result->fetch_assoc();
    } else {
        $actionMessage = "Tournament not found!";
    }
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournamentId = intval($_POST['tournament_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $registrationDeadline = $conn->real_escape_string($_POST['registration_deadline']);
    $price = $conn->real_escape_string($_POST['price']);
    $prize1st = $conn->real_escape_string($_POST['prize_1st']);
    $prize2nd = $conn->real_escape_string($_POST['prize_2nd']);
    $thumbnailPath = $editTournament['thumbnail'];

    // Handle file upload if a new file is selected
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $thumbnailPath = '../uploads/' . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath);
    }

    // Update tournament details
    $query = "UPDATE tournaments 
              SET name='$name', category='$category', date='$date', time='$time', 
                  registration_deadline='$registrationDeadline', price='$price', 
                  prize_1st='$prize1st', prize_2nd='$prize2nd', thumbnail='$thumbnailPath'
              WHERE id=$tournamentId";

    if ($conn->query($query)) {
        $actionMessage = "Tournament updated successfully!";
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
    <title>Edit Tournament</title>
    
    <link href="../css/tournament.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="upper-form">
        <h2>Edit Tournament</h2>
        <?php if ($actionMessage): ?>
            <div class="message"><?php echo $actionMessage; ?></div>
        <?php endif; ?>

        <?php if ($editTournament): ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tournament_id" value="<?php echo $editTournament['id']; ?>">

                <div class="form-group">
                    <label>Tournament Name:</label>
                    <input type="text" name="name" value="<?php echo $editTournament['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <select name="category" required>
                        <option value="PUBG" <?php echo ($editTournament['category'] === 'PUBG') ? 'selected' : ''; ?>>PUBG</option>
                        <option value="FreeFire" <?php echo ($editTournament['category'] === 'FreeFire') ? 'selected' : ''; ?>>FreeFire</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" name="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $editTournament['date']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Time:</label>
                    <input type="time" name="time" value="<?php echo $editTournament['time']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Registration Deadline:</label>
                    <input type="date" name="registration_deadline" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $editTournament['registration_deadline']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Entry Fee:</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $editTournament['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Prize for 1st Place:</label>
                    <input type="number" step="0.01" name="prize_1st" value="<?php echo $editTournament['prize_1st']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Prize for 2nd Place:</label>
                    <input type="number" step="0.01" name="prize_2nd" value="<?php echo $editTournament['prize_2nd']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Thumbnail:</label>
                    <div class="current-files">
                        Current: <img src="<?php echo $editTournament['thumbnail']; ?>" alt="Thumbnail" width="100">
                    </div>
                    <input type="file" name="thumbnail" accept="image/*">
                </div>
                <button type="submit" class="btn">Update Tournament</button>
                <a href="tournament-list.php" class="btn">Back to List</a>
            </form>
        <?php else: ?>
            <p>Tournament not found!</p>
            <a href="tournament-list.php" class="btn">Back to List</a>
        <?php endif; ?>
    </div>
        </div>
</body>
</html>
