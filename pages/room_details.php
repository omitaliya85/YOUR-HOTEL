<?php
session_start();
include('../config/db.php');

// ===================== Validate Room ID =====================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$room_id = intval($_GET['id']);

// ===================== Fetch Room Details =====================
$query = "SELECT * FROM rooms WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: rooms.php");
    exit();
}

$room = $result->fetch_assoc();
$imgPath = "../" . htmlspecialchars($room['image']);
$defaultImage = "../uploads/rooms/default-room.jpeg";

// ===================== Check If Image Exists =====================
if (empty($room['image']) || !file_exists($imgPath)) {
    $imgPath = $defaultImage;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($room['room_type']); ?> - YOUR HOTEL</title>

    <!-- ===================== GODMODE Room Details CSS ===================== -->
    <link rel="stylesheet" href="../assets/css/room_details_nitro.css">

    <!-- ===================== Native JS ===================== -->
    <script defer src="../assets/js/room_details_nitro.js"></script>
</head>

<body class="godmode-room-page">

    <!-- ===================== Include Header ===================== -->
    <?php include('../includes/header.php'); ?>

    <!-- ===================== Room Details Section ===================== -->
    <section class="godmode-room-details">
        <div class="godmode-room-container">

            <!-- ===================== Room Image ===================== -->
            <div class="godmode-room-img-container">
                <img src="<?= $imgPath; ?>" alt="<?= htmlspecialchars($room['room_type']); ?>"
                    class="godmode-room-img" onerror="this.onerror=null; this.src='<?= $defaultImage; ?>';">
            </div>

            <!-- ===================== Room Content ===================== -->
            <div class="godmode-room-content">
                <h1 class="godmode-room-title"><?= htmlspecialchars($room['room_type']); ?></h1>
                <p class="godmode-room-description"><?= nl2br(htmlspecialchars($room['description'])); ?></p>

                <ul class="godmode-room-features">
                    <li><i class="fas fa-users"></i> Capacity: <?= $room['capacity']; ?> People</li>
                    <li><i class="fas fa-check-circle"></i> Availability: <?= ucfirst($room['availability_status']); ?></li>
                    <li><i class="fas fa-concierge-bell"></i> Amenities: <?= htmlspecialchars($room['amenities'] ?: 'N/A'); ?></li>
                </ul>

                <p class="godmode-room-price">Starting from <strong>₹<?= number_format($room['price'], 2); ?></strong> per night</p>

                <!-- ===================== Booking and Back Buttons ===================== -->
                <a href="booking.php?room_id=<?= $room['id']; ?>" class="godmode-room-btn">Book This Room</a>
                <a href="rooms.php" class="godmode-back-btn">Back to Rooms</a>
            </div>
        </div>
    </section>

    <!-- ===================== Include Footer ===================== -->
    <?php include('../includes/footer.php'); ?>
</body>

</html>