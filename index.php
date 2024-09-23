<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metro Events - Organize and Join Events</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Header */
        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
        }

        /* Hero Section */
        .hero {
            background: url('https://source.unsplash.com/1600x900/?events') center center/cover no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero h1 {
            color: blue;
            font-size: 4rem;
            font-weight: 700;
        }

        .hero p {
            color: blue;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .hero a {
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
        }

        /* Features Section */
        .features {
            padding: 50px 0;
            background-color: #f8f9fa;
        }

        .features h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
            font-weight: bold;
        }

        .feature-box {
            padding: 30px;
            text-align: center;
        }

        .feature-box i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }

        .feature-box h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        /* Call to Action */
        .cta {
            background-color: #007bff;
            padding: 50px;
            text-align: center;
        }

        .cta h2 {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta p {
            color: #f8f9fa;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .cta a {
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
        }

        /* Footer */
        footer {
            padding: 30px 0;
            text-align: center;
            background-color: #f8f9fa;
            color: #6c757d;
        }

        footer a {
            color: #007bff;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Metro Events</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cta">Get Started</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Organize & Join Amazing Events</h1>
            <p>Discover events, connect with others, and create memorable experiences.</p>
            <a href="#cta" class="btn btn-primary">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2>Why Use Metro Events?</h2>
            <div class="row">
                <div class="col-md-4 feature-box">
                    <i class="fas fa-calendar-alt"></i>
                    <h4>Organize Events</h4>
                    <p>Create and manage events with ease, invite participants, and track responses.</p>
                </div>
                <div class="col-md-4 feature-box">
                    <i class="fas fa-users"></i>
                    <h4>Join Activities</h4>
                    <p>Discover local activities and join communities that share your interests.</p>
                </div>
                <div class="col-md-4 feature-box">
                    <i class="fas fa-bell"></i>
                    <h4>Get Notified</h4>
                    <p>Receive real-time notifications about event changes, updates, and reminders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Create an account or log in to explore the best events in your area.</p>
            <a href="register.php" class="btn btn-light">Sign Up</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Metro Events. All Rights Reserved.</p>
            <p>Questions? <a href="#">Contact Us</a></p>
        </div>
    </footer>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
