<?php
session_start();
include('db.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $request_value = "declined"; // Set the value for the request condition

    // Check if the connection is successful
    if ($conn === false) {
        die("Error: Could not connect. " . mysqli_connect_error());
    }

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete related records from the `participation_requests` table
        $delete_related_query = "DELETE FROM participation_requests WHERE event_id = ? ";
        $stmt = $conn->prepare($delete_related_query);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();

        // Step 2: Delete the event record
        $delete_event_query = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($delete_event_query);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        $_SESSION['message'] = "Event deleted successfully.";
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        $_SESSION['error'] = "An error occurred while trying to delete the records. Error: " . $e->getMessage();
    }

    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
