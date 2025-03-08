<?php
$host = 'sqlserver'; // MySQL service name in docker-compose
$user = 'root';
$password = 'rootpassword';
$dbname = 'battlezone';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to MySQL successfully!";
?>