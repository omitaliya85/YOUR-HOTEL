<?php
session_start();
include('../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - YOUR HOTEL</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom Room Styles -->
    <link rel="stylesheet" href="../assets/css/rooms.css">
</head>

<body class="dark-theme">

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- =================== Rooms Section =================== -->
    <section class="rooms-section">
        <h2 class="section-title">Explore Our Luxurious Rooms</h2>

        <!-- Filter Options -->
        <div class="filter-container">
            <select id="filterStatus" class="form-control filter-dropdown">
                <option value="all">All</option>
                <option value="available">Available</option>
                <option value="booked">Booked</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <!-- Room Cards -->
        <div id="room-container" class="room-grid"></div>

        <!-- Pagination -->
        <div id="pagination" class="pagination-container"></div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

    <!-- jQuery and Bootstrap JS -->
    <!-- jQuery Minified -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom Room JS -->
    <script src="../assets/js/rooms.js"></script>
</body>

</html>