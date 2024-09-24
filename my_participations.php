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

$user_id = $_SESSION['user_id'];

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
        .container {
            margin-top: 20px;
        }
        .dashboard-header {
            margin-bottom: 20px;
        }
        .nav-link {
            color: #007bff;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
    </style>';

echo '<div class="container">';
echo '<div class="dashboard-header">';
echo '<h2>User Dashboard</h2>';
echo '<p>Here are the events you have been approved to join.</p>';
echo '</div>';

echo '<nav class="nav">';
echo '<a class="nav-link" href="events.php">View Events</a>';
echo '<a class="nav-link" href="my_participations.php">My Participations</a>';
echo '<form method="POST" action="logout.php">';
echo '<button type="submit" class="btn btn-danger">Logout</button>';
echo '</form>';
echo '</nav>';

// Display approved events in a table
echo '<h3>Approved Participations</h3>';

echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Title</th>';
echo '<th>Description</th>';
echo '<th>Date</th>';
echo '<th>Location</th>';
echo '<th>Organizer</th>';
echo '</tr>';
echo '</thead>';
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
echo '</tbody>';
echo '</table>';
echo '</div>';

// Include Bootstrap JS and dependencies
echo '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">';
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    echo '</div>';
}

if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">';
    echo $_SESSION['message'];
    unset($_SESSION['message']);
    echo '</div>';
}
  ob_end_flush();  
?>
