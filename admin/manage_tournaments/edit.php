<?php
include "../connection.php";

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_room'])) {
    $room_id = $conn->real_escape_string($_POST['room_id']);
    $room_password = $conn->real_escape_string($_POST['room_password']);
    $description = $conn->real_escape_string($_POST['description']);
    $room_db_id = $conn->real_escape_string($_POST['room_db_id']);
    
    if (!is_numeric($room_password)) {
        $message = "Password must be an integer.";
    } else {
        $sql = "UPDATE room_details SET room_id='$room_id', room_password='$room_password', description='$description' WHERE id='$room_db_id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Room details updated successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch room details
$rooms_result = $conn->query("SELECT * FROM room_details ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
        }
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Existing Rooms</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Room ID</th>
            <th>Password</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($room = $rooms_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $room['room_id']; ?></td>
            <td><?php echo $room['room_password']; ?></td>
            <td><?php echo $room['description']; ?></td>
            <td>
                <button onclick="openEditModal('<?php echo $room['id']; ?>', '<?php echo $room['room_id']; ?>', '<?php echo $room['room_password']; ?>', '<?php echo $room['description']; ?>')">Edit</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Room</h2>
            <form method="post">
                <input type="hidden" id="room_db_id" name="room_db_id">
                <label>Room ID:</label>
                <input type="text" id="edit_room_id" name="room_id" required>
                <label>Password:</label>
                <input type="text" id="edit_room_password" name="room_password" required>
                <label>Description:</label>
                <textarea id="edit_description" name="description" required></textarea>
                <button type="submit" name="update_room">Update</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, roomId, password, description) {
            document.getElementById('room_db_id').value = id;
            document.getElementById('edit_room_id').value = roomId;
            document.getElementById('edit_room_password').value = password;
            document.getElementById('edit_description').value = description;
            document.getElementById('editModal').style.display = 'block';
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>
