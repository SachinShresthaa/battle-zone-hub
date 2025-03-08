<?php
session_start();

$entryFee = isset($_GET['entry_fee']) ? floatval($_GET['entry_fee']) : 0;

// Get tournament and team details from session
$tournament_name = isset($_SESSION['tournament_name']) ? $_SESSION['tournament_name'] : 'Unknown Tournament';
$team_name = isset($_SESSION['team_name']) ? $_SESSION['team_name'] : 'Unknown Team';

// Check if user is logged in and fetch user details
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    $user_id = $_SESSION['user_id'];
    $user_email = $_SESSION['user_email'];

    // Fetch the user's name from the database using the user_id
    include_once '../connection.php'; // Make sure to include your DB connection file

    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_name);
    $stmt->fetch();
    $stmt->close();
} else {
    // If not logged in, show an error message or redirect to login
    $_SESSION['validate_msg'] = "You must be logged in to access this page.";
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Displaying any session messages
if (isset($_SESSION['transaction_msg'])) {
    echo $_SESSION['transaction_msg'];
    unset($_SESSION['transaction_msg']);
}

if (isset($_SESSION['validate_msg'])) {
    echo $_SESSION['validate_msg'];
    unset($_SESSION['validate_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khalti Payment Integration</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert link -->
</head>

<body class="m-4">
    <h1 class="text-center">Khalti Payment Integration</h1>

    <div class="d-flex justify-content-center mt-3">
        <form class="row g-3 w-50 mt-4" action="payment-request.php" method="POST">
            
            <!-- Hidden fields for user_id, teamName, and tournamentName -->
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <input type="hidden" name="teamName" value="<?php echo htmlspecialchars($team_name); ?>">
            <input type="hidden" name="tournamentName" value="<?php echo htmlspecialchars($tournament_name); ?>">

            <!-- Display Team Name and Tournament Name in a column -->
          
            <!-- Amount and Order Details -->
            <div class="col-md-6">
                <label for="inputAmount4" class="form-label">Amount</label>
                <input type="number" class="form-control" id="inputAmount4" name="inputAmount4" value="<?php echo htmlspecialchars($entryFee); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="inputPurchasedOrderId4" class="form-label">Team Name</label>
                <input type="text" class="form-control" id="inputPurchasedOrderId4" name="inputPurchasedOrderId4" value="<?php echo htmlspecialchars($team_name); ?>" required>
            </div>

            <div class="col-12">
                <label for="inputPurchasedOrderName" class="form-label">Tournament Name</label>
                <input type="text" class="form-control" id="inputPurchasedOrderName" name="inputPurchasedOrderName" value="<?php echo htmlspecialchars($tournament_name); ?>" required>
            </div>

            <!-- Customer Details -->
            <label for="">Customer Details:</label>
            <div class="col-12">
                <label for="inputName" class="form-label">Name</label>
                <input type="text" class="form-control" id="inputName" name="inputName" value="<?php echo htmlspecialchars($user_name); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo htmlspecialchars($user_email); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="inputPhone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="inputPhone" name="inputPhone" required>
            </div>

            <!-- Display the entry fee below the form -->
            <div class="col-12">
                <button type="submit" name="submit" class="btn btn-primary">Pay with Khalti</button>
            </div>
        </form>
    </div>
</body>

</html>
