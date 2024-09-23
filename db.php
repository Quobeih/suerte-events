<?php
/* db.php
$servername = "suerte-db.mysql.database.azure.com";
$username = "suerte";  // Replace with your MySQL username
$password = 'Quobeih2021';  // Replace with your MySQL password
$dbname = "suerte-database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/

$conn = mysqli_init();
mysqli_ssl_set($con,NULL,NULL, "{path to CA cert}", NULL, NULL);
mysqli_real_connect($conn, "suerte-db.mysql.database.azure.com", "suerte", "Quobeih2021", "suertedbazure", 3306, MYSQLI_CLIENT_SSL);
?>
