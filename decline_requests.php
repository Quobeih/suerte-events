<?php
ob_start();
include('db.php');

$request_id = $_GET['request_id'];

// Update request status to 'declined'
$update_query = "UPDATE participation_requests SET status = 'declined' WHERE id = '$request_id'";
mysqli_query($conn, $update_query);

header("Location: dashboard.php");
exit;
ob_end_flush();
?>
