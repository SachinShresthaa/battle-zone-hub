<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's booking details
$user_id = $_SESSION['user_id'];
$matches = [];

// Query to get the registered matches for the logged-in user
$sql = "SELECT t.id, t.name AS tournament_name, t.date AS tournament_date, m.room_id, m.room_password 
        FROM matches m
        JOIN tournaments t ON m.tournament_id = t.id
        WHERE m.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $matches[] = $row;
    }
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking</title>
    <link rel="stylesheet" href="./CSS/my_booking.css">
</head>
<body>
    <?php include 'Header.php'; ?>

    <div class="main-container">
        <h1>My Registered Matches</h1>

        <?php if (count($matches) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tournament Name</th>
                        <th>Tournament Date</th>
                        <th>Room ID</th>
                        <th>Room Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matches as $match): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($match['tournament_name']); ?></td>
                            <td><?php echo htmlspecialchars($match['tournament_date']); ?></td>
                            <td><?php echo htmlspecialchars($match['room_id']); ?></td>
                            <td><?php echo htmlspecialchars($match['room_password']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no registered matches.</p>
        <?php endif; ?>
    </div>

    <?php include 'Footer.php'; ?>
</body>
</html>
