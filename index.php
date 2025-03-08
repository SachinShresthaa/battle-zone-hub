<?php
$host = 'localhost'; // MySQL service name in docker-compose
$user = 'root';
$password = '';
$dbname = 'battlezone';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to MySQL successfully!";
?>