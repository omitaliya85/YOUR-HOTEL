<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// CSRF Token Validation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Cancel Booking Action
if (isset($_GET['cancel'], $_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $booking_id = intval($_GET['cancel']);
    if ($booking_id > 0) {
        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $booking_id, $user_id);
        if ($stmt->execute()) {
            $success_msg = "Booking cancelled successfully!";
        } else {
            $error_msg = "Error cancelling booking.";
        }
        $stmt->close();
    }
}

// Fetch user bookings
$query = "SELECT bookings.id, rooms.room_type, bookings.check_in, bookings.check_out, 
                 bookings.total_nights, bookings.total_price, bookings.payment_type, 
                 bookings.payment_status, bookings.status, bookings.notes
          FROM bookings
          JOIN rooms ON bookings.room_id = rooms.id
          WHERE bookings.user_id = ?
          ORDER BY bookings.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Your Hotel</title>
    <link rel="stylesheet" href="../assets/css/my_booking.css">
    <script src="../assets/js/my_booking.js" defer></script>
</head>

<body>
    <div class="booking-container">
        <h1>My Bookings</h1>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_msg)) : ?>
            <p class="message success"><?= htmlspecialchars($success_msg); ?></p>
        <?php endif; ?>

        <?php if (!empty($error_msg)) : ?>
            <p class="message error"><?= htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <div class="booking-list">
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="booking-card">
                        <div class="booking-info">
                            <h2><?= htmlspecialchars($row['room_type']); ?></h2>
                            <p><strong>Check-in:</strong> <?= htmlspecialchars($row['check_in']); ?></p>
                            <p><strong>Check-out:</strong> <?= htmlspecialchars($row['check_out']); ?></p>
                            <p><strong>Total Nights:</strong> <?= htmlspecialchars($row['total_nights']); ?></p>
                            <p><strong>Total Price:</strong> $<?= number_format((float)$row['total_price'], 2); ?></p>
                        </div>

                        <div class="booking-status">
                            <p><strong>Payment Type:</strong> <?= ucfirst(htmlspecialchars($row['payment_type'])); ?></p>
                            <p><strong>Payment Status:</strong>
                                <span class="status <?= htmlspecialchars($row['payment_status']); ?>">
                                    <?= ucfirst(htmlspecialchars($row['payment_status'])); ?>
                                </span>
                            </p>
                            <p><strong>Booking Status:</strong>
                                <span class="status <?= htmlspecialchars($row['status']); ?>">
                                    <?= ucfirst(htmlspecialchars($row['status'])); ?>
                                </span>
                            </p>
                            <?php if (!empty($row['notes'])) : ?>
                                <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($row['notes'])); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Cancel Button -->
                        <?php if ($row['status'] == 'pending' || $row['status'] == 'confirmed') : ?>
                            <a href="my_booking.php?cancel=<?= urlencode($row['id']); ?>&token=<?= htmlspecialchars($_SESSION['csrf_token']); ?>"
                                class="btn cancel-btn"
                                onclick="return confirm('Are you sure you want to cancel this booking?');">
                                Cancel Booking
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-bookings">No bookings found!</p>
            <?php endif; ?>
        </div>

        <!-- Back to Home Button -->
        <a href="../pages/index.php" class="back-btn">Back to Home</a>
    </div>
</body>

</html>