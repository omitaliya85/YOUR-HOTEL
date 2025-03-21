<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

// CSRF Token Validation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle booking deletion with CSRF protection
if (isset($_GET['delete'], $_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: manage_bookings.php?success=Booking+deleted+successfully");
            exit();
        } else {
            $error_msg = "Error deleting booking.";
        }
        $stmt->close();
    }
}

// Handle booking update with CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'], $_POST['token']) && $_POST['token'] === $_SESSION['csrf_token']) {
    $id = intval($_POST['booking_id']);
    $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
    $payment_status = htmlspecialchars($_POST['payment_status'], ENT_QUOTES, 'UTF-8');
    $notes = htmlspecialchars($_POST['notes'], ENT_QUOTES, 'UTF-8');

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ?, payment_status = ?, notes = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status, $payment_status, $notes, $id);
        if ($stmt->execute()) {
            header("Location: manage_bookings.php?success=Booking+updated+successfully");
            exit();
        } else {
            $error_msg = "Error updating booking.";
        }
        $stmt->close();
    }
}

// Fetch all bookings
$query = "SELECT bookings.id, users.name AS user_name, users.email, rooms.room_type, bookings.check_in, bookings.check_out, 
                 bookings.total_nights, bookings.total_price, bookings.status, bookings.payment_type, bookings.payment_status, bookings.notes
          FROM bookings 
          JOIN users ON bookings.user_id = users.id 
          JOIN rooms ON bookings.room_id = rooms.id 
          ORDER BY bookings.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: #1e293b;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #2d3748;
            padding: 20px;
            border-radius: 12px;
            width: 95%;
            max-width: 1200px;
        }

        h1 {
            text-align: center;
            color: #f0e68c;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #4a5568;
        }

        table th {
            background: #4a5568;
            color: #f0e68c;
        }

        table tr:nth-child(even) {
            background: #374151;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
        }

        .btn-primary {
            background: #3b82f6;
        }

        .btn-delete {
            background: #ef4444;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #4a5568;
            color: #f0e68c;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }

        .message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
        }

        .success {
            background: #38a169;
        }

        .error {
            background: #e53e3e;
        }

        /* Edit Form */
        .edit-form {
            background: #374151;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .edit-form input,
        .edit-form select,
        .edit-form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background: #1f2937;
            color: #e2e8f0;
            border: 1px solid #4b5563;
            border-radius: 5px;
        }

        .edit-form button {
            margin-top: 10px;
            padding: 10px 20px;
            background: #3b82f6;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Bookings</h1>

        <?php if (!empty($success_msg)): ?>
            <p class="message success"><?= htmlspecialchars($success_msg); ?></p>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <p class="message error"><?= htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Nights</th>
                    <th>Total Price</th>
                    <th>Payment</th>
                    <th>Booking Status</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['user_name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['room_type']); ?></td>
                        <td><?= htmlspecialchars($row['check_in']); ?></td>
                        <td><?= htmlspecialchars($row['check_out']); ?></td>
                        <td><?= htmlspecialchars($row['total_nights']); ?></td>
                        <td>$<?= number_format((float)$row['total_price'], 2); ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['payment_type'])); ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                        <td><?= nl2br(htmlspecialchars($row['notes'])); ?></td>
                        <td>
                            <a href="manage_bookings.php?edit=<?= urlencode($row['id']); ?>" class="btn btn-edit">Edit</a>
                            <a href="manage_bookings.php?delete=<?= urlencode($row['id']); ?>&token=<?= htmlspecialchars($_SESSION['csrf_token']); ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>

                    <!-- Inline Edit Form -->
                    <?php if (isset($_GET['edit']) && intval($_GET['edit']) === intval($row['id'])): ?>
                        <tr>
                            <td colspan="12">
                                <form method="POST" class="edit-form">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?= $row['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="checked-in" <?= $row['status'] === 'checked-in' ? 'selected' : ''; ?>>Checked-In</option>
                                        <option value="checked-out" <?= $row['status'] === 'checked-out' ? 'selected' : ''; ?>>Checked-Out</option>
                                        <option value="cancelled" <?= $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>

                                    <label>Payment Status</label>
                                    <select name="payment_status">
                                        <option value="pending" <?= $row['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?= $row['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="failed" <?= $row['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                    </select>

                                    <label>Notes</label>
                                    <textarea name="notes" rows="3"><?= htmlspecialchars($row['notes']); ?></textarea>

                                    <button type="submit" name="update_booking">Update Booking</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>

</html>