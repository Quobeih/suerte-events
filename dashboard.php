<?php
ob_start();
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

// Fetch unread notifications
$sql_notifications = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_notifications = $conn->prepare($sql_notifications);
$stmt_notifications->bind_param("i", $user_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
$unread_count = $result_notifications->fetch_assoc()['unread_count'];

// Fetch the events the user has requested to join and are approved
$sql_participations = "SELECT events.*, users.name AS organizer_name 
                       FROM events 
                       JOIN participation_requests ON events.id = participation_requests.event_id
                       JOIN users ON events.organizer_id = users.id
                       WHERE participation_requests.user_id = ? AND participation_requests.status = 'accepted'";
$stmt_participations = $conn->prepare($sql_participations);
$stmt_participations->bind_param("i", $user_id);
$stmt_participations->execute();
$result_participations = $stmt_participations->get_result();

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

// Main layout
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

// Content area
echo '<div class="content">';
if ($user_role == 'admin') {
    header('Location: admin_dashboard.php');
} elseif ($user_role == 'organizer') {
    header('Location: organizer_dashboard.php');
} else {
    // Content for regular users
    
    
    // Display accepted participation events
    echo '<h3>Your Accepted Events</h3>';
    if ($result_participations->num_rows > 0) {
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Title</th><th>Description</th><th>Date</th><th>Location</th><th>Organizer</th></tr></thead>';
        echo '<tbody>';
        while ($event = $result_participations->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($event['title']) . '</td>';
            echo '<td>' . htmlspecialchars($event['description']) . '</td>';
            echo '<td>' . htmlspecialchars($event['date']) . '</td>';
            echo '<td>' . htmlspecialchars($event['location']) . '</td>';
            echo '<td>' . htmlspecialchars($event['organizer_name']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="text-muted">You have not been accepted to any events.</p>';
    }
}
echo '</div>';
ob_end_flush();
?>

// Include Bootstrap JS and dependencies
echo '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';
?>
