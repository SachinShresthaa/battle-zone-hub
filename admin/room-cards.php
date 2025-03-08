<?php
include "../connection.php";

// Handle form submission for adding a new room
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_room'])) {
        $room_id = $conn->real_escape_string($_POST['room_id']);
        $room_password = $conn->real_escape_string($_POST['room_password']);
        $description = $conn->real_escape_string($_POST['description']);
        $tournament_id = $conn->real_escape_string($_POST['tournament_id']);

        // Check if a room already exists for this tournament
        $check_sql = "SELECT id FROM room_details WHERE tournament_id = '$tournament_id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $message = "A room has already been added for this tournament.";
        } elseif (!is_numeric($room_password)) {
            $message = "Password must be an integer.";
        } else {
            // Insert new room details with a timestamp
            $sql = "INSERT INTO room_details (room_id, room_password, description, tournament_id, created_at)
                    VALUES ('$room_id', '$room_password', '$description', '$tournament_id', NOW())";

            if ($conn->query($sql) === TRUE) {
                $message = "Room details added successfully!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Handle form submission for editing a room
    if (isset($_POST['edit_room'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $room_id = $conn->real_escape_string($_POST['room_id']);
        $room_password = $conn->real_escape_string($_POST['room_password']);
        $description = $conn->real_escape_string($_POST['description']);
        $tournament_id = $conn->real_escape_string($_POST['tournament_id']);

        // Update room details
        $sql = "UPDATE room_details SET room_id = '$room_id', room_password = '$room_password', description = '$description', tournament_id = '$tournament_id' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Room details updated successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch latest tournaments for both PUBG and FreeFire categories (limit 1 each)
$tournaments_result = $conn->query("SELECT id, name FROM tournaments WHERE category IN ('PUBG', 'FreeFire') ORDER BY category DESC, id DESC LIMIT 2");
$tournaments = [];
while ($row = $tournaments_result->fetch_assoc()) {
    $tournaments[] = $row;
}

// Delete expired rooms after 15 minutes
$sql_delete_expired = "DELETE FROM room_details WHERE created_at < NOW() - INTERVAL 15 MINUTE";
$conn->query($sql_delete_expired);

// Fetch room details with tournament names
$rooms_result = $conn->query("SELECT rd.id, rd.room_id, rd.room_password, rd.description, rd.tournament_id, rd.created_at, t.name as tournament_name
                              FROM room_details rd
                              JOIN tournaments t ON rd.tournament_id = t.id
                              ORDER BY rd.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <style>
        /* Form Container */
        .room-card {
            padding: 30px;
            width: 1000px;
            text-align: center;
            padding-left: 500px;
        }

        h2 {
            font-size: 26px;
            margin-bottom: 20px;
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
            width: 100%;
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

        /* Table Styles */
        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgb(153, 152, 152);
            color: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid white;
            text-align: center;
        }

        th {
            background-color: rgb(63, 62, 62);
            font-size: 20px;
        }

        td {
            background-color: rgb(221, 216, 216);
            font-size: 20px;
            font-weight: bold;
            color: black;
        }

        button {
            padding: 5px 10px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
        }

        table button:hover {
            background-color: darkred;
        }

        .space {
            height: 20vh;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #2E2E2E;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #444444;
            width: 50%;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: white;
        }
    </style>
</head>
<body>
    <div class="room-card">
        <h2>Room Details</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="room-id">Room ID:</label>
                <input type="text" id="room-id" name="room_id" placeholder="Enter Room ID" required>
            </div>
            <div class="form-group">
                <label for="room-password">Password:</label>
                <input type="text" id="room-password" name="room_password" placeholder="Enter Numeric Password" required>
            </div>
            <div class="form-group">
                <label for="tournament_id">Tournament:</label>
                <select id="tournament_id" name="tournament_id" required>
                    <option value="" disabled selected>Select a Tournament</option>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?php echo $tournament['id']; ?>"><?php echo $tournament['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Room Description:</label>
                <textarea id="description" name="description" placeholder="Enter Room Description" required></textarea>
            </div>
            <button type="submit" name="add_room" class="btn-submit">Add Room</button>
        </form>
        <h3>Existing Rooms</h3>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
    <table class="rooms-table">
        <tr>
            <th>Room ID</th>
            <th>Password</th>
            <th>Description</th>
            <th>Tournament</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($room = $rooms_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $room['room_id']; ?></td>
            <td><?php echo $room['room_password']; ?></td>
            <td><?php echo $room['description']; ?></td>
            <td><?php echo $room['tournament_name']; ?></td>
            <td><?php echo $room['created_at']; ?></td>
            <td>
                <button class="edit-btn" onclick="openEditModal(<?php echo $room['id']; ?>, '<?php echo $room['room_id']; ?>', '<?php echo $room['room_password']; ?>', '<?php echo $room['description']; ?>', '<?php echo $room['tournament_id']; ?>')">Edit</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div class="space"></div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Room Details</h2>
            <form action="" method="post">
                <input type="hidden" id="edit-id" name="id">
                <div class="form-group">
                    <label for="edit-room-id">Room ID:</label>
                    <input type="text" id="edit-room-id" name="room_id" placeholder="Enter Room ID" required>
                </div>
                <div class="form-group">
                    <label for="edit-room-password">Password:</label>
                    <input type="text" id="edit-room-password" name="room_password" placeholder="Enter Numeric Password" required>
                </div>
                <div class="form-group">
                    <label for="edit-tournament_id">Tournament:</label>
                    <select id="edit-tournament_id" name="tournament_id" required>
                        <option value="" disabled selected>Select a Tournament</option>
                        <?php foreach ($tournaments as $tournament): ?>
                            <option value="<?php echo $tournament['id']; ?>"><?php echo $tournament['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-description">Room Description:</label>
                    <textarea id="edit-description" name="description" placeholder="Enter Room Description" required></textarea>
                </div>
                <button type="submit" name="edit_room" class="btn-submit">Update Room</button>
            </form>
        </div>
    </div>

    <script>
        // Function to open the edit modal
        function openEditModal(id, roomId, roomPassword, description, tournamentId) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-room-id').value = roomId;
            document.getElementById('edit-room-password').value = roomPassword;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-tournament_id').value = tournamentId;
            document.getElementById('editModal').style.display = 'block';
        }

        // Function to close the edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>