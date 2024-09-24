<?php
ob_start();
// Include the database connection
include 'db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Password hashing
   // $role = $_POST['role'];  // Capture the selected role

    // Insert user into database
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'user')";
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Successfully Registered. Please Login."); window.location.href = "login.php";</script>';
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
    ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            max-width: 400px;
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
            User Registration
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                 
                <button type="submit" class="btn btn-primary">Register</button>
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
