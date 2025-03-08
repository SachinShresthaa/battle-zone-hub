<?php
session_start();
include "../connection.php"; // Ensure this file defines $conn

// Ensure database connection
if (!isset($conn)) {
    die("Error: Database connection failed.");
}

// Insert team registration data if available
if (isset($_SESSION['registration_data'])) {
    $query = "INSERT INTO pubg_team_registration 
        (team_name, tournament_id, member1_name, member1_uid, member2_name, member2_uid, member3_name, member3_uid, member4_name, member4_uid, user_id, email, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sissssssssis",
        $_SESSION['registration_data']['teamName'],
        $_SESSION['registration_data']['tournament_id'],
        $_SESSION['registration_data']['member1Name'],
        $_SESSION['registration_data']['member1UID'],
        $_SESSION['registration_data']['member2Name'],
        $_SESSION['registration_data']['member2UID'],
        $_SESSION['registration_data']['member3Name'],
        $_SESSION['registration_data']['member3UID'],
        $_SESSION['registration_data']['member4Name'],
        $_SESSION['registration_data']['member4UID'],
        $_SESSION['registration_data']['user_id'],
        $_SESSION['registration_data']['user_email']
    );
    $stmt->execute();
    $stmt->close();

    // Set success message in session
    $_SESSION['registration_msg'] = 'Your team has been successfully registered for the tournament.';
}

// Unset session variables after storing them
unset($_SESSION['registration_data']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="mt-5 d-flex justify-content-center">
        <div class="mb-3">
            <img src="payment-success.jpg" class="img-fluid" alt="Payment Success">
            <div class="card">
                <div class="card-body text-white bg-success">
                    <h5 class="card-title">You have successfully registered in tournament</h5>
                    <p class="card-text">
                        <?php
                        if (isset($_SESSION['registration_msg'])) {
                            unset($_SESSION['registration_msg']); // Unset the message after displaying
                        }
                        ?>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="../Pubg.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>