<?php
include("../connection.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM users WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User deleted successfully'); window.location.href='adminPanel.php';</script>";
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>
