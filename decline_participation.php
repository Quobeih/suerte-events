<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organizer') {
    echo "Access denied!";
    exit;
}

$request_id = $_POST['request_id'];

// Update participation request status to declined
$sql = "UPDATE participation_requests SET status = 'declined' WHERE id = '$request_id'";
if ($conn->query($sql) === TRUE) {
    echo "Participation declined!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
