<?php
include "../connection.php";

// Delete rooms where created_at is older than 15 minutes
$sql = "DELETE FROM room_details WHERE created_at < NOW() - INTERVAL 15 MINUTE";

if ($conn->query($sql) === TRUE) {
    echo "Expired rooms deleted successfully.";
} else {
    echo "Error: " . $conn->error;
}
?>
