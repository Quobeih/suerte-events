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

<div class="content">
    <div class="dashboard-header">
        <?php if ($user_role == 'organizer') { ?>
            <h2>Organizer Dashboard</h2>
            <p>Manage your events and view participants.</p>
             <a href="create_event.php">
                <button class="button btn-primary" >Create Event </button>
             </a>
            <!-- Display Events -->
            <h3>Your Events</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $result_events->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td>
                            <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h2>Welcome, <?php echo htmlspecialchars($user_name); ?></h2>
            <p>Explore upcoming events and manage your participation.</p>
        <?php } ?>
    </div>
    
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php } ?>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php } ?>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
