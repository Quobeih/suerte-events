<?php
ob_start();
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];

// Check if the user has already requested to join the event
$sql_check = "SELECT * FROM participation_requests WHERE user_id = ? AND event_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $event_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // User has already requested to join the event
    echo '<script>alert("You have already requested to join this event!"); window.location.href = "dashboard.php";</script>';
} else {
    // Insert participation request
    $sql = "INSERT INTO participation_requests (user_id, event_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $event_id);
    if ($stmt->execute()) {
        // Request submitted successfully
        echo '<script>alert("Request to join the event submitted successfully!"); window.location.href = "dashboard.php";</script>';
    } else {
        // Error occurred
        echo '<script>alert("Error: ' . addslashes($conn->error) . '"); window.location.href = "dashboard.php";</script>';
    }
}
$stmt_check->close();
$stmt->close();
$conn->close();
ob_end_flush();
?>
