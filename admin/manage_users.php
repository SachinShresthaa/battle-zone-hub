
<?php
include("../connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="CSS/styles.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="Main">
        <h1>Manage Users</h1>
        <div class="Body">
            <?php
            // Fetch users
            $query = "SELECT id, fullname, email, created_at FROM users";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Registered At</th><th>Actions</th></tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['fullname']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['created_at']}</td>";
                    echo "<td>
                            <a href='delete_user.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
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
