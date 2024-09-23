<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    echo "<div class='alert alert-danger'>Access denied!</div>";
    exit;
}

// Fetch count of organizers
$sql_count_organizers = "SELECT COUNT(*) AS total_organizers FROM users WHERE role = 'organizer'";
$result_organizers = $conn->query($sql_count_organizers);
$total_organizers = $result_organizers->fetch_assoc()['total_organizers'];

// Fetch count of users
$sql_count_users = "SELECT COUNT(*) AS total_users FROM users WHERE role = 'user'";
$result_users = $conn->query($sql_count_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch count of events
$sql_count_events = "SELECT COUNT(*) AS total_events FROM events";
$result_events = $conn->query($sql_count_events);
$total_events = $result_events->fetch_assoc()['total_events'];

// Fetch all pending organizer requests (already existing code)
$sql_organizer_requests = "SELECT * FROM users WHERE role = 'user'";
$organizer_requests = $conn->query($sql_organizer_requests);

// Fetch all pending participation requests (already existing code)
$sql_participation_requests = "SELECT participation_requests.*, users.name AS user_name, events.title AS event_title
                               FROM participation_requests
                               JOIN users ON participation_requests.user_id = users.id
                               JOIN events ON participation_requests.event_id = events.id
                               WHERE participation_requests.status = 'pending'";
$participation_requests = $conn->query($sql_participation_requests);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            margin-top: 20px;
        }
        .side-nav {
            height: 100vh;
            background-color: #343a40;
            padding: 15px;
        }
        .side-nav a {
            color: #ffffff;
            display: block;
            padding: 10px 15px;
            text-decoration: none;
        }
        .side-nav a:hover {
            background-color: #495057;
        }
        .side-nav .nav-header {
            color: #ced4da;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .content-area {
            margin-left: 250px;
            padding: 20px;
        }
        .card-deck .card {
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Side Navigation Panel -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="side-nav">
                    <h4 class="nav-header">Admin Panel</h4>
                    <a href="admin_dashboard.php">Dashboard</a>
                    <a href="organizers.php">Organizers</a>
                    <a href="manage_events.php">Manage Events</a>
                    <a href="requests.php">Requests</a>
                    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ml-sm-auto col-lg-10 content-area">
                <h2>Admin Dashboard</h2>
                
                <!-- Statistics Cards -->
                <div class="card-deck">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Organizers</h5>
                            <p class="card-text"><?php echo $total_organizers; ?></p>
                        </div>
                    </div>
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text"><?php echo $total_users; ?></p>
                        </div>
                    </div>
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Events</h5>
                            <p class="card-text"><?php echo $total_events; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Organizer Requests -->
                 

                <!-- Participation Requests -->

            </main>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
