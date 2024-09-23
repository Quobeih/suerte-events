<?php
// db.php
$servername = "suerte-server";
$username = "nzkvxouelm";  // Replace with your MySQL username
$password = 'Yvr3mQy$o3jdDAnr';  // Replace with your MySQL password
$dbname = "suerte-database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
