<?php
include("connection.php");

if (isset($_POST['submit'])) {
    $fullname = htmlspecialchars(trim($_POST["fname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (Fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    try {
        $stmt->execute();
        header("Location: login.php?signup=success");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "Unable to register: " . $e->getMessage();
    }

    $stmt->close();
    mysqli_close($conn);
}
?>
