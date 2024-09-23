<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'organizer') {
    echo "Access denied!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $organizer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO events (title, description, date, location, organizer_id)
            VALUES ('$title', '$description', '$date', '$location', '$organizer_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>
    <h2>Create Event</h2>
    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Description:</label>
        <textarea name="description" required></textarea><br>
        <label>Date:</label>
        <input type="datetime-local" name="date" required><br>
        <label>Location:</label>
        <input type="text" name="location" required><br>
        <button type="submit">Create Event</button>
    </form>
</body>
</html>
