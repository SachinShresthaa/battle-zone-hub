<?php
include("../connection.php");

$actionMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $query = "DELETE FROM users WHERE id = $userId";

    if ($conn->query($query)) {
        $actionMessage = "User deleted successfully.";
    } else {
        $actionMessage = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="./CSS/users.css" rel="stylesheet">
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            const actionMessage = "<?php echo $actionMessage; ?>";
            if (actionMessage) {
                alert(actionMessage);
            }
        });

        function confirmDeletion(form) {
            if (confirm("Are you sure you want to delete this user?")) {
                form.submit();
            }
        }
    </script>
</head>
<body>
    <div class="Main">
        <div class="head">
            <h1>MANAGE USERS</h1>
        </div>
        <div class="Body">
            <?php
            $query = "SELECT id, fullname, email, created_at FROM users";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr>
                        <th>SN</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                      </tr>";

                $sn = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$sn}</td>";
                    echo "<td>{$row['fullname']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['created_at']}</td>";
                    echo "<td>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='user_id' value='{$row['id']}'>
                                <a href='#' onclick='confirmDeletion(this.closest(\"form\"));'>Delete</a>
                            </form>
                          </td>";
                    echo "</tr>";
                    $sn++;
                }
                echo "</table>";
            } else {
                echo "<p>No users found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
