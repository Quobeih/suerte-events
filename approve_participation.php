<?php
ob_start();
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organizer') {
    echo "Access denied!";
    exit;
}

$request_id = $_POST['request_id'];

// Update participation request status to accepted
$sql = "UPDATE participation_requests SET status = 'accepted' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);

if ($stmt->execute()) {
    // Fetch the user_id related to this request
    $sql_fetch_user = "SELECT user_id FROM participation_requests WHERE id = ?";
    $stmt_fetch_user = $conn->prepare($sql_fetch_user);
    $stmt_fetch_user->bind_param("i", $request_id);
    $stmt_fetch_user->execute();
    $result_fetch_user = $stmt_fetch_user->get_result();
    
    if ($result_fetch_user->num_rows > 0) {
        $row = $result_fetch_user->fetch_assoc();
        $user_id = $row['user_id'];

        // Insert a notification for the user
        $notification_message = "Your participation request has been approved!";
        $sql_insert_notification = "INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)";
        $stmt_insert_notification = $conn->prepare($sql_insert_notification);
        $stmt_insert_notification->bind_param("is", $user_id, $notification_message);

        if ($stmt_insert_notification->execute()) {
            echo "Participation approved successfully and notification sent!";
        } else {
            echo "Participation approved, but there was an error sending the notification: " . $stmt_insert_notification->error;
        }
    } else {
        echo "Participation approved, but user not found for this request.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
ob_end_flush();
?>

