<?php
// Database configuration
$servername = "localhost"; // Change to your database server
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "battlezonehub"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $conn->real_escape_string($_POST['room_id']);
    $room_password = $conn->real_escape_string($_POST['room_password']);
    $category = $conn->real_escape_string($_POST['category']);

    // Validate category
    if (!in_array($category, ['PUBG', 'FreeFire'])) {
        $message = "Invalid category.";
    } else {
        // Check if the password is a valid integer
        if (!is_numeric($room_password)) {
            $message = "Password must be an integer.";
        } else {
            // Insert data into the database with integer password
            $sql = "INSERT INTO room_details (room_id, room_password, category)
                    VALUES ('$room_id', '$room_password', '$category')";

            if ($conn->query($sql) === TRUE) {
                $message = "Room details added successfully!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Room Details</title>
    <style>
        /* General styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .room-card {
            background: #ffffff;
            padding: 20px 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        .room-card h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #333;
        }
        .room-form .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .room-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9rem;
            color: #555;
        }
        .room-form input,
        .room-form select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
        }
        .room-form input:focus,
        .room-form select:focus {
            border-color: #007bff;
        }
        .btn-submit {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .room-form select {
            background-color: #fff;
            cursor: pointer;
        }
        .message {
            margin-top: 15px;
            color: green;
            font-size: 0.9rem;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="room-card">
        <h2>Room Details</h2>
        <form action="" method="post" class="room-form">
            <div class="form-group">
                <label for="room-id">Room ID:</label>
                <input type="text" id="room-id" name="room_id" placeholder="Enter Room ID" required>
            </div>
            <div class="form-group">
                <label for="room-password">Password:</label>
                <input type="text" id="room-password" name="room_password" placeholder="Enter Room Password (Numeric)" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <option value="PUBG">PUBG</option>
                    <option value="FreeFire">FreeFire</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Add Room</button>
            </div>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? '' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
