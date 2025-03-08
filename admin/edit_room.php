<?php
include "../connection.php";
include "../Header.php";

// Get the room ID from the URL
if (!isset($_GET['id'])) {
    die("Room ID is missing.");
}
$room_id = $_GET['id'];

// Fetch room details from the database
$sql = "SELECT rd.id, rd.room_id, rd.room_password, rd.description, rd.tournament_id, rd.created_at, t.name as tournament_name
        FROM room_details rd
        JOIN tournaments t ON rd.tournament_id = t.id
        WHERE rd.id = '$room_id'";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Room not found.");
}

$room = $result->fetch_assoc();

// Handle form submission to update room details
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $conn->real_escape_string($_POST['room_id']);
    $room_password = $conn->real_escape_string($_POST['room_password']);
    $description = $conn->real_escape_string($_POST['description']);
    $tournament_id = $conn->real_escape_string($_POST['tournament_id']);

    // Update the room details
    $update_sql = "UPDATE room_details 
                   SET room_id = '$room_id', room_password = '$room_password', description = '$description', tournament_id = '$tournament_id' 
                   WHERE id = '$room_id'";

    if ($conn->query($update_sql) === TRUE) {
        $message = "Room details updated successfully!";
        // Redirect to the room details page or display updated values
        header("Location: ./index.php/room_cards.php");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch the tournaments again for the dropdown
$tournaments_result = $conn->query("SELECT id, name FROM tournaments WHERE category IN ('PUBG', 'FreeFire') ORDER BY category DESC, id DESC LIMIT 2");
$tournaments = [];
while ($row = $tournaments_result->fetch_assoc()) {
    $tournaments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room Details</title>
    <style>
        body{
            background-color:black;
        }
       .room-card {
            padding: 30px;
            width: 1000px;
            text-align: center;
            padding-left: 500px;
        }

        h2 {
            font-size: 26px;
            margin-bottom: 20px;
            color:white;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-size: 20px;
            display: block;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 80%;
            padding: 15px;
            background-color: #2E2E2E;
            border: 1px solid #444444;
            border-radius: 8px;
            font-size: 20px;
            color: white;
            outline: none;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: rgb(229, 23, 23);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: rgb(255, 49, 49);
            box-shadow: 0 0 10px rgba(255, 49, 49, 0.6);
        }

        /* Message Styles */
        .message {
            font-size: 16px;
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #ff4c4c;
            color: white;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

    </style>
</head>
<body>
    <div class="room-card">
        <h2>Edit Room Details</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="room-id">Room ID:</label>
                <input type="text" id="room-id" name="room_id" value="<?php echo $room['room_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="room-password">Password:</label>
                <input type="text" id="room-password" name="room_password" value="<?php echo $room['room_password']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tournament_id">Tournament:</label>
                <select id="tournament_id" name="tournament_id" required>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?php echo $tournament['id']; ?>" <?php echo ($tournament['id'] == $room['tournament_id']) ? 'selected' : ''; ?>>
                            <?php echo $tournament['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Room Description:</label>
                <textarea id="description" name="description" required><?php echo $room['description']; ?></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Update Room</button>
        </form>

        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'updated successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>