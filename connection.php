<?php
$hostname = "sqlserver"; 
$dbuser = "root";
$dbpassword = "rootpassword"; 
$dbname = "battlezone";

$conn = mysqli_connect($hostname, $dbuser, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connection successful!";
}
?>