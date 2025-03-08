<?php
session_start();
include_once '../connection.php';

// Check if payment data exists in the session
if (!isset($_SESSION['payment_details'])) {
    die("No payment details found.");
}

// Get payment details from the session
$payment_details = $_SESSION['payment_details'];
$transaction_uuid = $payment_details['transaction_uuid']; // Transaction UUID
$total_amount = $payment_details['amount']; // Amount paid
$user_id = $_SESSION['user_id']; // User ID (from session)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        .container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); display: inline-block; }
        h1 { color: #28a745; }
        .details { margin-top: 20px; text-align: left; display: inline-block; }
        .button { margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Successful!</h1>
        <p>Your tournament registration has been confirmed.</p>
        <div class="details">
            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?php echo htmlspecialchars($total_amount); ?></p>
            <p><strong>Payment Method:</strong> eSewa</p>
        </div>
        <a href="index.php" class="button">Return to Home</a>
    </div>
</body>
</html>

<?php

// Now, you can proceed to process the payment details (for example, store them in the database)

if (isset($_GET['data'])) {

    // Decode the Base64 encoded data
    $decodedData = base64_decode($_GET['data']);
    
    // Convert JSON string to PHP array
    $transactionData = json_decode($decodedData, true);

    if ($transactionData && isset($transactionData['status']) && $transactionData['status'] === 'COMPLETE') {
        try {
            // Start transaction
            $conn->begin_transaction();

            // Extract payment details
            $transaction_uuid = $transactionData['transaction_uuid'] ?? '';
            $total_amount = $transactionData['total_amount'] ?? 0;
            $payment_method = 'eSewa';
            $payment_status = 'COMPLETED';

            // Prepare the insert statement without tournament_id
            $stmt = $conn->prepare("
                INSERT INTO payments (user_id, transaction_uuid, total_amount, status, payment_method)
                VALUES (?, ?, ?, ?, ?)
            ");

            // Bind parameters to the SQL query
            $stmt->bind_param("issds", $user_id, $transaction_uuid, $total_amount, $payment_status, $payment_method);

            // Execute the statement
            if ($stmt->execute()) {
                // Commit transaction
                $conn->commit();

                // Clear session variables
                unset($_SESSION['payment_details']);  // Clear payment details from session

                // Display success message
                echo "<script>alert('Payment recorded successfully.');</script>";
            } else {
                throw new Exception("Failed to insert payment record.");
            }

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            die("Error processing payment: " . $e->getMessage());
        }

    } else {
        // Redirect to failure page if payment is not complete
        header("Location: failure.php");
        exit();
    }

} else {
    // Redirect to home page if no data is found
    header("Location: index.php");
    exit();
}
?>
