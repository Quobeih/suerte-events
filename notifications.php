<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include 'db.php';

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

// Fetch all notifications for the logged-in user
$sql_notifications = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt_notifications = $conn->prepare($sql_notifications);
$stmt_notifications->bind_param("i", $user_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
$notifications = $result_notifications->fetch_all(MYSQLI_ASSOC);

// Mark all notifications as read after fetching
$sql_mark_read = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt_mark_read = $conn->prepare($sql_mark_read);
$stmt_mark_read->bind_param("i", $user_id);
$stmt_mark_read->execute();

// Fetch unread notification count
$sql_unread_count = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_unread_count = $conn->prepare($sql_unread_count);
$stmt_unread_count->bind_param("i", $user_id);
$stmt_unread_count->execute();
$result_unread_count = $stmt_unread_count->get_result();
$unread_count = $result_unread_count->fetch_assoc()['unread_count'];

// Include Bootstrap CSS for styling
echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">';

echo '<style>
        body {
            display: flex;
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
    </style>';

// Sidebar
echo '<div class="sidebar">';
echo '<h4>Welcome, ' . htmlspecialchars($user_name) . '</h4>';
echo '<nav class="nav flex-column">';
echo '<a class="nav-link" href="dashboard.php">Dashboard</a>';
echo '<a class="nav-link" href="events.php">Events</a>';
echo '<a class="nav-link d-flex align-items-center" href="notifications.php">Notifications';
if ($unread_count > 0) {
    echo '<span class="notification-count ml-2">' . $unread_count . '</span>';
}
echo '</a>';
echo '<a class="nav-link" href="request_organizer.php">Request to be an Organizer</a>';

echo '<form method="POST" action="logout.php" class="mt-3">';
echo '<button type="submit" class="btn btn-danger">Logout</button>';
echo '</form>';
echo '</nav>';
echo '</div>';

// Main content area
echo '<div class="content">';
echo '<h2>Notifications</h2>';
if (!empty($notifications)) {
    echo '<ul class="list-group">';
    foreach ($notifications as $notification) {
        echo '<li class="list-group-item">';
        echo htmlspecialchars($notification['message']);
        echo ' <small class="text-muted">' . htmlspecialchars($notification['created_at']) . '</small>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p class="text-muted">No notifications.</p>';
}
echo '</div>';

// Include Bootstrap JS and dependencies
echo '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';
?>
