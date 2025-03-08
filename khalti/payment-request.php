<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
    $amount = $_POST['inputAmount4'] ?? null;
    $purchase_order_id = $_POST['inputPurchasedOrderId4'] ?? null;
    $purchase_order_name = $_POST['inputPurchasedOrderName'] ?? null;
    $name = $_POST['inputName'] ?? null;
    $email = $_POST['inputEmail'] ?? null;
    $phone = $_POST['inputPhone'] ?? null;

    
    if (empty($amount) || empty($purchase_order_id) || empty($purchase_order_name) || 
        empty($name) || empty($email) || empty($phone)) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "All fields are required",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        header("Location: checkout.php");
        exit;
    }

    if (!is_numeric($amount)) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Amount must be a number",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        header("Location: checkout.php");
        exit;
    }

    if (!is_numeric($phone) || strlen($phone) !== 10) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Phone must be a valid 10-digit number",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        header("Location: checkout.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Invalid email address",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        header("Location: checkout.php");
        exit;
    }

   
    $amount = (float)$amount * 100;

    
    $postFields = [ 
        "return_url" => "http://localhost/battle-zone-hub/khalti/payment-response.php",
        "website_url" => "http://localhost/khalti-payment/",
        "amount" => $amount,
        "purchase_order_id" => $purchase_order_id,
        "purchase_order_name" => $purchase_order_name,
        "customer_info" => [
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ]
    ];

    
    $jsonData = json_encode($postFields);

    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => [
            'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455', // Replace with your key from environment or config
            'Content-Type: application/json',
        ],
    ]);

    
    $response = curl_exec($curl);

    
    if (curl_errno($curl)) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Payment request failed: ' . curl_error($curl) . '",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        curl_close($curl);
        header("Location: checkout.php");
        exit;
    }

    curl_close($curl);

    
    $responseArray = json_decode($response, true);

    if (isset($responseArray['error'])) {
        $_SESSION['validate_msg'] = '<script>
        Swal.fire({
            icon: "error",
            title: "Payment Error: ' . htmlspecialchars($responseArray['error']) . '",
            showConfirmButton: false,
            timer: 1500
        });
        </script>';
        header("Location: checkout.php");
        exit;
    }

    if (isset($responseArray['payment_url'])) {
        
        header("Location: " . $responseArray['payment_url']);
        exit;
    }

    
    $_SESSION['validate_msg'] = '<script>
    Swal.fire({
        icon: "error",
        title: "Unexpected response from Khalti",
        showConfirmButton: false,
        timer: 1500
    });
    </script>';
    header("Location: checkout.php");
    exit;
}
?>