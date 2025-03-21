<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if booking data is passed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'], $_POST['check_in'], $_POST['check_out'], $_POST['total_price'])) {
    $room_id = intval($_POST['room_id']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = floatval($_POST['total_price']);
} else {
    header("Location: ../pages/rooms.php");
    exit();
}

// Process Payment
$success_msg = "";
$error_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $payment_type = htmlspecialchars($_POST['payment_type'], ENT_QUOTES, 'UTF-8');

    // Insert booking details
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, payment_type, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iissds", $user_id, $room_id, $check_in, $check_out, $total_price, $payment_type);

    if ($stmt->execute()) {
        $success_msg = "Booking confirmed! Payment pending.";
        header("Location: my_booking.php?success=Booking+confirmed");
        exit();
    } else {
        $error_msg = "Error while processing payment.";
    }
    $stmt->close();
}

// Get room details
$room_query = "SELECT room_type, price FROM rooms WHERE id = ?";
$stmt = $conn->prepare($room_query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room_result = $stmt->get_result();
$room_data = $room_result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Your Hotel</title>
    <link rel="stylesheet" href="../assets/css/payment.css">
    <script src="../assets/js/payment.js" defer></script>
</head>

<body>
    <div class="payment-container">
        <h1>Payment for Booking</h1>

        <?php if (!empty($success_msg)): ?>
            <p class="message success"><?= htmlspecialchars($success_msg); ?></p>
        <?php elseif (!empty($error_msg)): ?>
            <p class="message error"><?= htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <div class="payment-info">
            <h2><?= htmlspecialchars($room_data['room_type']); ?></h2>
            <p><strong>Check-in:</strong> <?= htmlspecialchars($check_in); ?></p>
            <p><strong>Check-out:</strong> <?= htmlspecialchars($check_out); ?></p>
            <p><strong>Total Price:</strong> $<?= number_format((float)$total_price, 2); ?></p>
        </div>

        <form method="POST" action="payment.php" class="payment-form">
            <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id); ?>">
            <input type="hidden" name="check_in" value="<?= htmlspecialchars($check_in); ?>">
            <input type="hidden" name="check_out" value="<?= htmlspecialchars($check_out); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price); ?>">

            <label for="payment_type">Select Payment Method:</label>
            <select name="payment_type" id="payment_type" class="payment-select" required>
                <option value="online">Online Payment</option>
                <option value="cash">Pay with Cash</option>
            </select>

            <button type="submit" name="confirm_payment" class="btn-confirm">Confirm & Pay</button>
        </form>

        <a href="../pages/rooms.php" class="back-btn">Back to Rooms</a>
    </div>
</body>

</html>