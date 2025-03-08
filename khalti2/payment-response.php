<?php
session_start();
include "../connection.php"; // Ensure this file defines $conn

// Get the pidx from the URL
$pidx = $_GET['pidx'] ?? null;
if (!$pidx) {
    die("Error: No payment ID provided.");
}

// Initialize cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
    CURLOPT_HTTPHEADER => [
        'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);
if ($response === false) {
    die("cURL Error: " . curl_error($curl));
}
curl_close($curl);

$responseArray = json_decode($response, true);
if (!$responseArray) {
    die("Error: Invalid response from Khalti API.");
}

$_SESSION['response'] = $responseArray;

// Extract payment details
$transaction_id = $responseArray['transaction_id'] ?? null;
$amount = ($responseArray['total_amount'] ?? 0) / 100; // Convert to rupees
$status = $responseArray['status'] ?? 'Unknown';
$payment_method = "Khalti";

// Debugging output (can be removed in production)
echo "Transaction ID: $transaction_id<br>";
echo "Amount: $amount<br>";
echo "Status: $status<br>";
echo "Payment Method: $payment_method<br>";

// Ensure database connection
if (!isset($conn)) {
    die("Error: Database connection failed.");
}

// Insert payment data into the database
$stmt = $conn->prepare('INSERT INTO payments (pidx, transaction_id, amount, status, payment_method, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('ssdss', $pidx, $transaction_id, $amount, $status, $payment_method);
$stmt->execute();

// Check if payment insert was successful
if ($stmt->affected_rows > 0) {
    // Set success message in session
    $_SESSION['transaction_msg'] = '<script>
        Swal.fire({
            icon: "success",
            title: "Transaction successful!",
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = "message.php";
        });
    </script>';

    // Redirect to message.php
    header("Location: message.php");
    exit();
} else {
    // Set error message in session
    $_SESSION['transaction_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Error storing payment.",
            showConfirmButton: false,
            timer: 1500
        });
    </script>';

    // Redirect back to checkout or any error page
    header("Location: checkout.php");
    exit();
}

$stmt->close();
?>
