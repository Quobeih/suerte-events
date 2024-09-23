<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the logged-in user's name and role
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT name, role FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$user_name = $user['name'];
$user_role = $user['role'];

// Fetch all events
$sql = "SELECT events.*, users.name AS organizer_name 
        FROM events 
        JOIN users ON events.organizer_id = users.id";
$result = $conn->query($sql);

// Fetch unread notification count
$sql_unread_count = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_unread_count = $conn->prepare($sql_unread_count);
$stmt_unread_count->bind_param("i", $user_id);
$stmt_unread_count->execute();
$result_unread_count = $stmt_unread_count->get_result();
$unread_count = $result_unread_count->fetch_assoc()['unread_count'];

// Handle organizer request submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reason'])) {
    $reason = trim($_POST['reason']);
    if (empty($reason)) {
        echo '<script>alert("Reason is required."); window.history.back();</script>';
        exit;
    }
    $sql_request = "INSERT INTO organizer_requests (user_id, reason, status) VALUES (?, ?, 'pending')";
    $stmt_request = $conn->prepare($sql_request);
    $stmt_request->bind_param("is", $user_id, $reason);
    if ($stmt_request->execute()) {
        echo '<script>alert("Your request to become an organizer has been submitted successfully."); window.location.href = "events.php";</script>';
    } else {
        echo '<script>alert("There was an error submitting your request. Please try again."); window.history.back();</script>';
    }
    $stmt_request->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding: 15px;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #adb5bd;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
            border-radius: 5px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .notification-count {
            background-color: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
            position: relative;
            top: -10px;
            left: -10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Welcome, <?php echo htmlspecialchars($user_name); ?></h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link active" href="events.php">Events</a>
            <a class="nav-link d-flex align-items-center" href="notifications.php">Notifications
                <?php if ($unread_count > 0) { ?>
                    <span class="notification-count ml-2"><?php echo $unread_count; ?></span>
                <?php } ?>
            </a>
            <a class="nav-link" href="request_organizer.php">Request to be an Organizer</a>
            <form method="POST" action="logout.php" class="mt-3">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </nav>
    </div>

    <div class="content">
        <div class="container">
             
            <!-- Request Organizer Form -->
            <h3>Request to be an Organizer</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="reason">Reason for Request:</label>
                    <textarea id="reason" name="reason" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
