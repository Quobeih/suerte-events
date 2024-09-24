<?php
ob_start();
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    echo "<div class='alert alert-danger'>Access denied!</div>";
    exit;
}

// Fetch all pending organizer requests
$sql_organizer_requests = "SELECT * FROM users WHERE role = 'user'";
$organizer_requests = $conn->query($sql_organizer_requests);

// Fetch all events
$sql_events = "SELECT * FROM events"; // Adjust this query if you have specific columns you want to fetch
$events = $conn->query($sql_events);

// Prepare an array to hold organizer IDs
$organizer_ids = [];

// Iterate through events to collect unique organizer IDs
while ($event = $events->fetch_assoc()) {
    $organizer_ids[] = $event['organizer_id'];
}

// Get unique organizer IDs
$organizer_ids = array_unique($organizer_ids);

// Fetch organizers based on the collected IDs
$organizers = [];
if (!empty($organizer_ids)) {
    $ids = implode(',', array_map('intval', $organizer_ids)); // Sanitize the IDs for the query
    $sql_organizer_requests = "SELECT * FROM users WHERE id IN ($ids)";
    $result_organizers = $conn->query($sql_organizer_requests);
    
    // Store organizers in an associative array by ID
    while ($organizer = $result_organizers->fetch_assoc()) {
        $organizers[$organizer['id']] = $organizer['name'];
    }
}

// Fetch all pending participation requests
$sql_participation_requests = "SELECT participation_requests.*, users.name AS user_name, events.title AS event_title
                               FROM participation_requests
                               JOIN users ON participation_requests.user_id = users.id
                               JOIN events ON participation_requests.event_id = events.id
                               WHERE participation_requests.status = 'pending'";
$participation_requests = $conn->query($sql_participation_requests);
ob_end_flush();
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
                <h2>Events</h2>
                
                <!-- All Events -->
                <div class="card mt-4">
                    <div class="card-header">
                        All Events
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Organizer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Reset events query
                                $events->data_seek(0); // Reset pointer to fetch events again
                                while($event = $events->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td><?php echo htmlspecialchars($organizers[$event['organizer_id']] ?? 'Unknown'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

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
