<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOUR HOTEL</title>

    <!-- Main Stylesheets -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Custom JS Files -->
    <script src="../assets/js/ui-header.js" defer></script>
    <script src="../assets/js/main.js" defer></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Orbitron', sans-serif;
            background: #1a202c;
            color: #ffffff;
        }

        /* Header Styles */
        .hotel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: linear-gradient(145deg, #2d3748, #1a202c);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
        }

        /* Logo and Name */
        .hotel-logo {
            display: flex;
            align-items: center;
        }

        .hotel-logo img {
            height: 50px;
            width: auto;
            border-radius: 8px;
        }

        .hotel-name {
            font-size: 1.5rem;
            color: #FFD700;
            margin-left: 10px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(255, 215, 0, 0.8);
        }

        /* Navbar Styles */
        .hotel-navbar {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        .hotel-nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .hotel-nav-links li {
            margin: 0;
        }

        .hotel-nav-item {
            text-decoration: none;
            color: #a0aec0;
            font-size: 1rem;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
        }

        .hotel-nav-item:hover {
            background: #4a5568;
            color: #FFD700;
        }

        /* Auth Buttons */
        .hotel-auth-buttons {
            display: flex;
            gap: 12px;
        }

        .hotel-btn {
            padding: 8px 16px;
            font-size: 0.95rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .hotel-btn-register {
            background: #00c853;
            color: #fff;
        }

        .hotel-btn-register:hover {
            background: #009624;
        }

        .hotel-btn-login {
            background: #1e88e5;
            color: #fff;
        }

        .hotel-btn-login:hover {
            background: #1565c0;
        }

        .hotel-btn-dashboard {
            background: #ff9800;
            color: #fff;
        }

        .hotel-btn-dashboard:hover {
            background: #fb8c00;
        }

        .hotel-btn-logout {
            background: #e53e3e;
            color: #fff;
        }

        .hotel-btn-logout:hover {
            background: #c53030;
        }

        /* Mobile Navigation */
        @media (max-width: 768px) {
            .hotel-header {
                flex-direction: column;
                gap: 10px;
                padding: 1rem;
            }

            .hotel-nav-links {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .hotel-auth-buttons {
                flex-direction: column;
                width: 100%;
                text-align: center;
            }

            .hotel-btn {
                width: 100%;
                margin-top: 5px;
            }
        }
    </style>
</head>

<body class="dark-theme">
    <header class="hotel-header">
        <!-- Logo and Hotel Name -->
        <div class="hotel-logo">
            <a href="../pages/index.php">
                <img src="../assets/images/logo/hotel-logo.jpg" alt="YOUR HOTEL">
            </a>
            <span class="hotel-name">YOUR HOTEL</span>
        </div>

        <!-- Navbar Links -->
        <nav class="hotel-navbar">
            <ul class="hotel-nav-links">
                <li><a href="../pages/index.php" class="hotel-nav-item">Home</a></li>
                <li><a href="../pages/rooms.php" class="hotel-nav-item">Rooms</a></li>
                <li><a href="../pages/about.php" class="hotel-nav-item">About</a></li>
                <li><a href="../pages/contact.php" class="hotel-nav-item">Contact</a></li>
            </ul>
        </nav>

        <!-- Authentication Buttons -->
        <div class="hotel-auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../pages/my_booking.php" class="hotel-btn hotel-btn-dashboard">My Bookings</a>
                <a href="../auth/logout.php" class="hotel-btn hotel-btn-logout">Logout</a>
            <?php else: ?>
                <a href="../auth/register.php" class="hotel-btn hotel-btn-register">Register</a>
                <a href="../auth/login.php" class="hotel-btn hotel-btn-login">Login</a>
            <?php endif; ?>
        </div>
    </header>
</body>

</html>