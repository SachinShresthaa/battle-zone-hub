<?php
// Database configuration
$servername = "localhost"; // Update this to your server details
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "battlezonehub"; // Your database name

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";
$rooms = [];

// Handle form submission for adding room details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_id = $conn->real_escape_string(trim($_POST['room_id']));
    $room_password = $conn->real_escape_string(trim($_POST['room_password']));
    $category = $conn->real_escape_string(trim($_POST['category']));

    // Validate category
    if (!in_array($category, ['PUBG', 'FreeFire'])) {
        $message = "Invalid category selected.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($room_password, PASSWORD_BCRYPT);

        // Insert data into the database
        $sql = "INSERT INTO room_details (room_id, room_password, category) VALUES ('$room_id', '$hashed_password', '$category')";
        if ($conn->query($sql) === TRUE) {
            $message = "Room details added successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle fetching room details based on category
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['category'])) {
    $category = $conn->real_escape_string(trim($_GET['category']));

    if (in_array($category, ['PUBG', 'FreeFire'])) {
        $sql = "SELECT room_id, room_password FROM room_details WHERE category = '$category'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Manager</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .room-card, .room-list {
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 350px;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            font-size: 0.9rem;
            color: green;
        }
        .error {
            color: red;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="room-card">
        <h2>Add Room Details</h2>
        <form method="POST">
            <label for="room_id">Room ID:</label>
            <input type="text" id="room_id" name="room_id" required>

            <label for="room_password">Room Password:</label>
            <input type="text" id="room_password" name="room_password" required>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="PUBG">PUBG</option>
                <option value="FreeFire">FreeFire</option>
            </select>

            <button type="submit" name="add_room">Add Room</button>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? '' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="room-list">
        <h2>Room Details</h2>
        <div>
            <a href="?category=PUBG" style="margin-right: 10px;">PUBG</a>
            <a href="?category=FreeFire">FreeFire</a>
        </div>
        <ul>
            <?php if (count($rooms) > 0): ?>
                <?php foreach ($rooms as $room): ?>
                    <li>
                        <strong>Room ID:</strong> <?php echo htmlspecialchars($room['room_id']); ?><br>
                        <strong>Password:</strong> <?php echo htmlspecialchars($room['room_password']); ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No rooms available for the selected category.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
