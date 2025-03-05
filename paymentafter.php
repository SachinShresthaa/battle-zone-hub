<?php

include "../headerWithProfile.php";
include_once '../connection.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch tournament and user details
$tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$entry_fee = isset($_GET['entry_fee']) ? $_GET['entry_fee'] : "0"; // Default entry fee

if ($tournament_id > 0) {
    $stmt = $conn->prepare("SELECT date, price FROM tournaments WHERE id = ?");
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $stmt->bind_result($tournament_date, $entryFee);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Invalid tournament ID.");
}

// Payment data
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email']; // Ensure this is set during login
$transaction_uuid = mt_rand(100000, 999999); // Unique transaction ID
$total_amount = $entry_fee;
$product_code = "EPAYTEST";
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";

// Signature generation (security step)
$s = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);
$_SESSION['signature'] = base64_encode($s);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
    <link rel="stylesheet" href="./CSS/payment.css">
</head>
<body>
    <div class="confirmation-container">
        <h1>Confirm Your Payment</h1>
        <p>You're about to register for a tournament.</p>
        <p><strong>Entry Fee:</strong> <?php echo $total_amount; ?> NPR</p>
        <p><strong>Tournament Date:</strong> <?php echo $tournament_date; ?></p>
        
        <form action="payment_process.php" method="POST">
            <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
            <input type="hidden" name="entry_fee" value="<?php echo $total_amount; ?>">
            <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
            <input type="hidden" name="signature" value="<?php echo base64_encode($s); ?>">
            <button type="submit" class="pay-button">Proceed to Payment</button>
        </form>
        
        <a href="dashboard.php" class="cancel-link">Cancel</a>
    </div>
</body>
</html>
