<?php
session_start();
session_regenerate_id(true); // Prevent session fixation

include '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all required statistics in a single query
$query = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM bookings) AS total_bookings,
        (SELECT SUM(total_price) FROM bookings WHERE payment_status = 'paid') AS total_revenue,
        (SELECT COUNT(*) FROM rooms) AS total_rooms,
        (SELECT COUNT(*) FROM bookings WHERE status = 'pending') AS pending_bookings,
        (SELECT COUNT(*) FROM bookings WHERE status = 'checked-in') AS checked_in_guests
";

$result = mysqli_query($conn, $query);
$stats = mysqli_fetch_assoc($result);

$total_users = $stats['total_users'] ?? 0;
$total_bookings = $stats['total_bookings'] ?? 0;
$total_revenue = $stats['total_revenue'] ?? 0;
$total_rooms = $stats['total_rooms'] ?? 0;
$pending_bookings = $stats['pending_bookings'] ?? 0;
$checked_in_guests = $stats['checked_in_guests'] ?? 0;

// Determine greeting based on time
$hour = date('H');
$greeting = ($hour >= 5 && $hour < 12) ? "Good Morning" : (($hour >= 12 && $hour < 18) ? "Good Afternoon" : "Good Evening");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YOUR HOTEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- <link rel="stylesheet" href="../assets/css/admin_dashboard.css"> -->
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(120deg, rgb(122, 0, 0), rgb(0, 37, 100), rgb(216, 72, 0));
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-size: 300% 300%;
            animation: gradientAnimation 6s ease-in-out infinite;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Dashboard Container */
        .dashboard {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(145deg, #2d3748, #1a202c);
            border-radius: 20px;
            box-shadow: 10px 10px 25px #121217, -10px -10px 25px #4b4f5c;
            width: 90%;
            max-width: 1000px;
            opacity: 0;
            transform: scale(0.9);
            animation: fadeInScale 0.6s ease-in-out forwards;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Heading and Greeting */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0px 4px 10px rgba(160, 174, 192, 0.8);
            letter-spacing: 2px;
        }

        .greeting {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #e2e8f0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        nav a {
            text-decoration: none;
            color: #a0aec0;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            background: linear-gradient(145deg, #2e323d, #404552);
            box-shadow: 4px 4px 10px #1c1e26, -4px -4px 10px #555a66;
            transition: all 0.3s ease-in-out;
            text-align: center;
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out forwards 0.2s;
        }

        nav a:hover {
            color: #ffffff;
            background: linear-gradient(145deg, #404552, #2e323d);
            box-shadow: 0px 0px 15px rgba(192, 160, 185, 0.8);
            transform: translateY(-3px);
        }

        /* Stats Container - 2x3 Grid */
        .stat-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            /* Centers the container */
            justify-content: center;
            /* Ensures proper centering */
        }


        @media (max-width: 768px) {
            .stat-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stat-container {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        /* Stat Cards */
        .stat-card {
            padding: 1.5rem;
            background: linear-gradient(145deg, #1a202c, #2d3748);
            border-radius: 20px;
            box-shadow: 8px 8px 20px #121217, -8px -8px 20px #4b4f5c;
            transform-style: preserve-3d;
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
            text-align: center;
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out forwards 0.3s;
            cursor: pointer;
            position: relative;
        }

        /* Hover Effect - Glowing */
        .stat-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0px 0px 25px rgba(255, 255, 255, 0.5);
            background: radial-gradient(rgb(35, 43, 57), #1a202c);
        }

        /* Glow Effect on Hover */
        .stat-card::before {
            content: "";
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(45deg, rgba(255, 0, 0, 0.5), rgba(0, 0, 255, 0.5));
            filter: blur(15px);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #a0aec0;
            text-shadow: 0px 4px 10px rgba(160, 174, 192, 0.8);
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Logout Button */
        .logout {
            margin-top: 2rem;
        }

        .logout a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            color: #fff;
            border-radius: 10px;
            background: linear-gradient(145deg, #ff1a1a, #b30000);
            box-shadow: 4px 4px 10px #740000, -4px -4px 10px #ff4d4d;
            transition: all 0.3s ease-in-out;
            opacity: 0;
            animation: fadeIn 0.8s ease-in-out forwards 0.4s;
        }

        .logout a:hover {
            background: linear-gradient(145deg, #b30000, #ff1a1a);
            box-shadow: 0px 0px 15px rgba(255, 100, 100, 0.8);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>
        <p class="greeting"><?php echo htmlspecialchars($greeting); ?>, Admin!</p>

        <nav>
            <a href="manage_rooms.php">Manage Rooms</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="manage_users.php">Manage Users</a>
        </nav>

        <div class="stat-container">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo htmlspecialchars($total_users); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?php echo htmlspecialchars($total_bookings); ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Bookings</h3>
                <p><?php echo htmlspecialchars($pending_bookings); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Rooms</h3>
                <p><?php echo htmlspecialchars($total_rooms); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p><?php echo ($total_revenue !== null) ? '$' . number_format($total_revenue, 2) : 'N/A'; ?></p>
            </div>
            <div class="stat-card">
                <h3>Checked-in Guests</h3>
                <p><?php echo htmlspecialchars($checked_in_guests); ?></p>
            </div>
        </div>

        <div class="logout">
            <a href="admin_logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</body>


</html>