<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];

    if (isset($_POST['approve'])) {
        // Approve request: Update user's role and request status
        $sql_approve = "UPDATE users
                        JOIN organizer_requests ON users.id = organizer_requests.user_id
                        SET users.role = 'organizer', organizer_requests.status = 'approved'
                        WHERE organizer_requests.id = ?";
        $stmt_approve = $conn->prepare($sql_approve);
        $stmt_approve->bind_param("i", $request_id);
        if ($stmt_approve->execute()) {
            $_SESSION['message'] = "The user has been approved as an organizer.";
        } else {
            $_SESSION['error'] = "There was an error approving the request.";
        }
    } elseif (isset($_POST['reject'])) {
        // Reject request: Update request status to 'rejected'
        $sql_reject = "UPDATE organizer_requests SET status = 'rejected' WHERE id = ?";
        $stmt_reject = $conn->prepare($sql_reject);
        $stmt_reject->bind_param("i", $request_id);
        if ($stmt_reject->execute()) {
            $_SESSION['message'] = "The request has been rejected.";
        } else {
            $_SESSION['error'] = "There was an error rejecting the request.";
        }
    }

    header('Location: admin_organizer_requests.php');
    exit;
}
?>
