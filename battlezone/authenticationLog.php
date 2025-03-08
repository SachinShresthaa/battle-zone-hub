<?php
include("connection.php");

try {
    if (empty($_POST['email'])) {
        throw new Exception("Email is required");
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);

    echo json_encode([
        'exists' => $row['count'] > 0,
        'email' => $email
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>