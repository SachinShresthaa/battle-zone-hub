<?php
$hostname = "localhost"; 
$dbuser = "root";
$dbpassword = ""; 
$dbname = "battlezonehub";

$conn = mysqli_connect($hostname, $dbuser, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connection successful!";
}
?>