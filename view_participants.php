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

// Fetch participants for events organized by the user
$sql_participants = "SELECT p.id, u.name AS participant_name, e.title AS event_name 
                     FROM participation_requests p 
                     JOIN users u ON p.user_id = u.id 
                     JOIN events e ON p.event_id = e.id 
                     WHERE e.organizer_id = ? AND status='accepted'";
$stmt_participants = $conn->prepare($sql_participants);

// Check if the preparation was successful
if ($stmt_participants === false) {
    die('Error preparing the SQL statement: ' . $conn->error);
}

$stmt_participants->bind_param("i", $user_id);
$stmt_participants->execute();
$result_participants = $stmt_participants->get_result();

// Include Bootstrap CSS for styling
echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">';
ob_end_flush();
?>

<style>
    body {
        display: flex;
        flex-direction: row;
        height: 100vh;
        background-color: #f8f9fa;
    }
    .sidebar {
        width: 250px;
        background-color: #343a40;
        color: white;
        padding: 15px;
    }
    .sidebar a {
        color: white;
        display: block;
        padding: 10px;
        text-decoration: none;
    }
    .sidebar a:hover {
        background-color: #495057;
    }
    .content {
        flex-grow: 1;
        padding: 20px;
    }
</style>

<div class="sidebar">
    <h4>Welcome, <?php echo htmlspecialchars($user_name); ?></h4>
    <?php if ($user_role == 'admin') { ?>
        <a href="admin_dashboard.php">Admin Dashboard</a>
    <?php } elseif ($user_role == 'organizer') { ?>
        <a href="organizer_dashboard.php">Events</a>
        <a href="view_requests.php">View Requests</a>
        <a href="view_participants.php">View Participants</a>
    <?php } ?>
     
    <form method="POST" action="logout.php" style="margin-top: 20px;">
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>

<div class="container mt-5">
    <?php if ($user_role == 'organizer') { ?>
        <h1>Participants</h1>

        <?php if ($result_participants->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Participant Name</th>
                        <th>Event Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($participant = $result_participants->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($participant['participant_name']); ?></td>
                            <td><?php echo htmlspecialchars($participant['event_name']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No participants registered for your events at the moment.</p>
        <?php endif; ?>
    <?php } ?>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
