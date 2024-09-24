<?php
ob_start();

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
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
    <!-- Bootstrap CSS -->
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
        .event-table {
            margin-top: 20px;
        }
        .btn-request {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
        }
        .btn-request:hover {
            background-color: #0056b3;
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
    <!-- Sidebar -->
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
            <a class="nav-link active" href="request_organizer.php">Request to be an Organizer</a>
            <form method="POST" action="logout.php" class="mt-3">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </nav>
    </div>

    <!-- Main content area -->
    <div class="content">
        <div class="container">
            <h2>All Events</h2>
            <table class="table table-striped event-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Organizer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($event = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo htmlspecialchars($event['organizer_name']); ?></td>
                        <td>
                            <form method="POST" action="join_event.php">
                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                <button type="submit" class="btn btn-request">Request to Join</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
