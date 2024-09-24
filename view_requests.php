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

// Handle organizer request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_organizer'])) {
    $sql_check_request = "SELECT id FROM organizer_requests WHERE user_id = ? AND status = 'pending'";
    $stmt_check_request = $conn->prepare($sql_check_request);
    $stmt_check_request->bind_param("i", $user_id);
    $stmt_check_request->execute();
    $result_check_request = $stmt_check_request->get_result();

    if ($result_check_request->num_rows == 0) {
        $sql_request = "INSERT INTO organizer_requests (user_id) VALUES (?)";
        $stmt_request = $conn->prepare($sql_request);
        $stmt_request->bind_param("i", $user_id);
        if ($stmt_request->execute()) {
            $_SESSION['message'] = "Your request to become an organizer has been submitted.";
        } else {
            $_SESSION['error'] = "There was an error submitting your request. Please try again.";
        }
    } else {
        $_SESSION['message'] = "You have already requested to become an organizer. Please wait for approval.";
    }
}

// Fetch all events organized by the user
$sql_events = "SELECT events.*, users.name AS organizer_name 
                FROM events 
                JOIN users ON events.organizer_id = users.id
                WHERE events.organizer_id = ?";
$stmt_events = $conn->prepare($sql_events);
$stmt_events->bind_param("i", $user_id);
$stmt_events->execute();
$result_events = $stmt_events->get_result();

// Fetch pending participant requests
$requests_query = "SELECT r.id, u.name as user_name, e.title as event_name 
                   FROM participation_requests r 
                   JOIN users u ON r.user_id = u.id 
                   JOIN events e ON r.event_id = e.id 
                   WHERE e.organizer_id = ? AND r.status = 'pending'";
$stmt_requests = $conn->prepare($requests_query);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$requests = $stmt_requests->get_result();

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
    <?php if ($user_role != 'organizer') { ?>
        <form method="POST" action="" class="mt-3">
            <button type="submit" name="request_organizer" class="btn btn-primary">Request to Become Organizer</button>
        </form>
    <?php } ?>
</div>

<div class="container mt-5">
    <?php if ($user_role == 'organizer') { ?>
         

        <h1>Pending Participants Requests</h1>
       
        <?php if ($requests->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Event Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($request = $requests->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['event_name']); ?></td>
                            <td>
                                <a href="approve_request.php?request_id=<?php echo $request['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="decline_requests.php?request_id=<?php echo $request['id']; ?>" class="btn btn-danger btn-sm">Decline</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending requests at the moment.</p>
        <?php endif; ?>
    <?php } else { ?>
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?></h2>
        <p>Explore upcoming events and manage your participation.</p>
    <?php } ?>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
