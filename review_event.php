<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $review = $_POST['review'];
    $upvote = isset($_POST['upvote']) ? 1 : 0;

    $sql = "INSERT INTO reviews (user_id, event_id, review, upvote)
            VALUES ('$user_id', '$event_id', '$review', '$upvote')";

    if ($conn->query($sql) === TRUE) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave a Review</title>
</head>
<body>
    <h2>Leave a Review</h2>
    <form method="POST" action="">
        <label>Review:</label>
        <textarea name="review" required></textarea><br>
        <label>Upvote:</label>
        <input type="checkbox" name="upvote"><br>
        <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>">
        <button type="submit">Submit Review</button>
    </form>
</body>
</html>
