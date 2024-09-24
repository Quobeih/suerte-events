<?php
ob_start();
include 'db.php';
session_start();

$alertMessage = '';
$alertType = '';

if ($_SESSION['role'] != 'organizer') {
    echo "<div class='alert alert-danger'>Access denied!</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $organizer_id = $_SESSION['user_id'];

    // Insert event into the database
    $sql = "INSERT INTO events (title, description, date, location, organizer_id)
            VALUES ('$title', '$description', '$date', '$location', '$organizer_id')";
    
    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Event created successfully!";
        $alertType = "success";
    } else {
        $alertMessage = "Error: " . $conn->error;
        $alertType = "danger";
    }
}
    

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-size: 24px;
            padding: 20px;
        }
        .card-body {
            padding: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px;
        }
        .btn-back {
            background-color: #6c757d;
            border: none;
            border-radius: 5px;
            padding: 10px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            Create Event
        </div>
        <div class="card-body">
            <?php if ($alertMessage): ?>
                <script>
                    alert("<?php echo $alertMessage; ?>");
                    window.location.href = 'dashboard.php';
                </script>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="datetime-local" id="date" name="date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
                <button type="button" class="btn btn-back" onclick="window.history.back();">Back</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
