<?php
session_start();
include('../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to YOUR HOTEL</title>

    <!-- Main Stylesheets -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Hero, Features, and Testimonials JS -->
    <script src="../assets/js/ui-hero.js" defer></script>
    <script src="../assets/js/ui-features.js" defer></script>
    <script src="../assets/js/ui-testimonials.js" defer></script>

    <!-- jQuery CDN for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="dark-theme">

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- Hero Section -->
    <?php include('../includes/hero_section.php'); ?>

    <!-- =================== Featured Rooms Section =================== -->
    <section class="hotel-featured-rooms">
        <h2 class="hotel-section-title">Explore Our Top Rooms</h2>

        <!-- =================== Room Container =================== -->
        <div class="featured-rooms-container" id="room-container">
            <?php
            // Fetch 5 Random Available Rooms
            $query = "SELECT id, room_type, price, image FROM rooms WHERE availability_status = 'available' ORDER BY RAND() LIMIT 5";
            $result = mysqli_query($conn, $query);
            $roomCount = mysqli_num_rows($result);

            if ($roomCount > 0) {
                echo '<div class="room-grid">';
                while ($room = mysqli_fetch_assoc($result)) {
                    $imgPath = "../" . htmlspecialchars($room['image']);
                    $defaultImage = "../uploads/rooms/default-room.jpeg";

                    // Check if image exists, else use default
                    if (empty($room['image']) || !file_exists($imgPath)) {
                        $imgPath = $defaultImage;
                    }
            ?>
                    <div class="room-card">
                        <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>" class="room-img"
                            onerror="this.onerror=null; this.src='<?php echo $defaultImage; ?>';">
                        <h3><?php echo htmlspecialchars($room['room_type']); ?></h3>
                        <p>Starting from <strong>₹<?php echo number_format($room['price'], 2); ?></strong> per night</p>
                        <a href="room_details.php?id=<?php echo $room['id']; ?>" class="btn btn-primary room-btn">View Details</a>
                    </div>
            <?php
                }
                echo '</div>'; // Close room-grid
            } else {
                echo "<p class='no-rooms'>No rooms available at the moment. Please check back later.</p>";
            }
            ?>
        </div>

        <!-- Reload Button -->
        <div class="reload-btn-container">
            <button id="reload-btn" class="btn btn-secondary">♻️ Reload Rooms</button>
        </div>
    </section>

    <!-- Hotel Features -->
    <?php include('../includes/features.php'); ?>

    <!-- Guest Testimonials -->
    <?php include('../includes/testimonials.php'); ?>

    <!-- =================== Call-to-Action Section =================== -->
    <section class="hotel-cta">
        <div class="cta-content">
            <h2>Experience Unmatched Luxury</h2>
            <p>Book your stay now and enjoy premium comfort with top-notch services.</p>
            <div class="cta-btn-group">
                <a href="booking.php" class="btn btn-primary cta-btn">Book Now</a>
                <a href="rooms.php" class="btn btn-secondary cta-btn">Explore Rooms</a>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

    <!-- Scroll to Top Button -->
    <button id="backToTop" title="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Back to Top JS -->
    <script src="../assets/js/ui-backtotop.js" defer></script>

    <!-- =================== AJAX Reload Script =================== -->
    <script>
        // Reload Rooms using AJAX
        $('#reload-btn').on('click', function() {
            $.ajax({
                url: 'index.php', // Reload same file
                type: 'GET',
                success: function(response) {
                    let newRooms = $(response).find('#room-container').html();
                    $('#room-container').html(newRooms);
                },
                error: function() {
                    alert('⚠️ Failed to reload rooms. Please try again.');
                }
            });
        });
    </script>
</body>

</html>