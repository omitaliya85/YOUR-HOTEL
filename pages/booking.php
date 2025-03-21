<?php
session_start();
require_once "../config/db.php";

// Redirect if user not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch available rooms
$roomQuery = "SELECT id, room_type, price, description, availability_status, image FROM rooms WHERE availability_status = 'available'";
$rooms = $conn->query($roomQuery);

// Handle booking form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION['user_id'];
    $roomId = intval($_POST['booking_room_id']);
    $checkInDate = $_POST['booking_check_in'];
    $checkOutDate = $_POST['booking_check_out'];
    $paymentType = $_POST['booking_payment_type'];

    // Calculate total price based on room price and nights
    $roomData = $conn->query("SELECT price FROM rooms WHERE id = $roomId")->fetch_assoc();
    $pricePerNight = $roomData['price'];
    $totalNights = (strtotime($checkOutDate) - strtotime($checkInDate)) / 86400;
    $totalPrice = $pricePerNight * $totalNights;

    // Insert booking data
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, payment_type, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iissds", $userId, $roomId, $checkInDate, $checkOutDate, $totalPrice, $paymentType);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking successful! Waiting for confirmation.";
        header("Location: my_booking.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to book the room. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay | YOUR HOTEL</title>
    <link rel="stylesheet" href="../assets/css/booking.css">
    <script src="../assets/js/booking.js" defer></script>
</head>

<body class="booking-body">
    <?php include "../includes/header.php"; ?>

    <section class="booking-section">
        <div class="booking-container">
            <h1 class="booking-heading">Book Your Stay at <span class="booking-highlight">YOUR HOTEL</span></h1>

            <?php if (!empty($_SESSION['success'])) : ?>
                <p class="booking-success-msg"><?= htmlspecialchars($_SESSION['success']); ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php elseif (!empty($_SESSION['error'])) : ?>
                <p class="booking-error-msg"><?= htmlspecialchars($_SESSION['error']); ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="booking.php" method="POST" class="booking-form">
                <div class="booking-input-group">
                    <label for="booking_room_id">Select Room:</label>
                    <select name="booking_room_id" id="booking_room_id" required>
                        <?php while ($room = $rooms->fetch_assoc()) : ?>
                            <option value="<?= $room['id']; ?>">
                                <?= htmlspecialchars($room['room_type']); ?> - ₹<?= number_format($room['price'], 2); ?>/night
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="booking-input-group">
                    <label for="booking_check_in">Check-in Date:</label>
                    <input type="date" name="booking_check_in" id="booking_check_in" required>
                </div>

                <div class="booking-input-group">
                    <label for="booking_check_out">Check-out Date:</label>
                    <input type="date" name="booking_check_out" id="booking_check_out" required>
                </div>

                <div class="booking-input-group">
                    <label for="booking_payment_type">Payment Method:</label>
                    <select name="booking_payment_type" id="booking_payment_type" required>
                        <option value="online">Online Payment</option>
                        <option value="cash">Pay at Hotel</option>
                    </select>
                </div>

                <button type="submit" class="booking-btn">Confirm Booking</button>
            </form>
        </div>
    </section>

    <?php include "../includes/footer.php"; ?>
</body>

</html>