<?php
include "headerWithProfile.php";
include_once 'connection.php'; // Database connection

// Fetch tournament and user details
$tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : 0;
$entry_fee = isset($_GET['entry_fee']) ? $_GET['entry_fee'] : "0"; // Default entry fee

// Fetch the tournament details
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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to proceed with the payment.");
}

// Payment data
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email']; // Ensure this is set during login
$transaction_uuid = mt_rand(100000, 999999); // Unique transaction ID
$total_amount = $entry_fee; // The total amount for payment
$product_code = "EPAYTEST"; // Static product code
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";

// Signature generation (security step)
$s = hash_hmac('sha256', $message, '8gBm/:&EnhH.1/q', true);
$_SESSION['signature'] = base64_encode($s);

// Store payment information in session
$_SESSION['payment_details'] = [
    'amount' => $total_amount,
    'transaction_uuid' => $transaction_uuid,
    'product_code' => $product_code
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Tournament Registration</title>
    <link rel="stylesheet" href="./CSS/payment.css">
</head>
<body>
    <div class="payment-option">
        <div class="available">
            <div class="available-system">
                <h1 class="payment-header">Select Payment System</h1>
            </div>
            <div class="payment">
                <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                    <input type="text" name="amount" value="<?php echo $total_amount;?>" required hidden>
                    <input type="text" name="tax_amount" value="0" required hidden>
                    <input type="text" name="total_amount" value="<?php echo $total_amount;?>" required hidden>
                    <input type="text" name="transaction_uuid" value="<?php echo $transaction_uuid;?>" required hidden>
                    <input type="text" name="product_code" value="<?php echo $product_code;?>" required hidden>
                    <input type="text" name="product_service_charge" value="0" required hidden>
                    <input type="text" name="product_delivery_charge" value="0" required hidden>
                    <input type="text" name="success_ur     l" value="https://localhost/battle-zone-hub/Esewa/success.php" required hidden>
                    <input type="text" name="failure_url" value="https://localhost/Project/SourceCode/failure.php" required hidden>
                    <input type="text" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required hidden>
                    <input type="text" name="signature" value="<?php echo base64_encode($s); ?>" required hidden>
                    <input type="image" src="esewa/esewa.jpg" name="esewa" alt="eSewa Payment" />
                </form>
            </div>
        </div>
        <div class="not-available">
            <div class="not-available-system">
                <h1 class="payment-header">Will be available soon!!</h1>
            </div>
            <div class="payment">
                <!-- Khalti Payment Testing -->
                <form action="khalti/checkout.php" method="POST" id="khalti-payment-form">
                    <input type="hidden" name="amount" value="<?php echo $total_amount * 100; ?>" /> <!-- Khalti expects amount in paisa -->
                    <input type="hidden" name="product_code" value="<?php echo $product_code; ?>" />
                    <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" />
                    <input type="hidden" name="user_email" value="<?php echo $user_email; ?>" />
                    <input type="hidden" name="success_url" value="https://localhost/esewa/success.php" />
                    <input type="hidden" name="failure_url" value="https://localhost/Project/SourceCode/failure.php" />
                    <input type="hidden" name="signature" value="<?php echo base64_encode($s); ?>" />
                    <!-- Khalti test image -->
                    <input type="submit" value="Pay with Khalti" />
                </form>
                <!-- Add Khalti payment logo -->
                <input type="image" src="../Photos/khalti.png" alt="Khalti Payment" onclick="document.getElementById('khalti-payment-form').submit();">
            </div>
        </div>
    </div>
</body>
</html>
  