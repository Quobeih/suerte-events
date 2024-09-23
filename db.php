<?php
echo "Hello World";

// Directly define your database credentials
$host = 'suerte-server.mysql.database.azure.com';
$db = 'suerte-database';
$user = 'nzkvxouelm';
$pass = 'Yvr3mQy$o3jdDAnr';

// Create a MySQLi connection
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); // Optional: Adjust if you have specific SSL certs
mysqli_real_connect($conn, $host, $user, $pass, $db, 3306, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
} 
echo "Connected successfully";
?>
