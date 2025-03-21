<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$success_msg = $error_msg = "";

$default_image = "uploads/rooms/default-room.jpeg";

// Handle room addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_type = trim($_POST['room_type']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $availability_status = $_POST['availability_status'];
    $capacity = $_POST['capacity'];
    $amenities = trim($_POST['amenities']);

    $image_name = $default_image;

    if (!empty($_FILES['room_image']['name'])) {
        $image_extension = strtolower(pathinfo($_FILES['room_image']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_extension, $allowed_types)) {
            $image_name = time() . '_' . basename($_FILES['room_image']['name']);
            $image_target = "../uploads/rooms/" . $image_name;
            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $image_target)) {
                $image_name = "uploads/rooms/" . $image_name;
            } else {
                $error_msg = "Failed to upload image.";
            }
        } else {
            $error_msg = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        }
    }

    if (empty($error_msg)) {
        $stmt = $conn->prepare("INSERT INTO rooms (room_type, price, description, availability_status, capacity, amenities, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssiss", $room_type, $price, $description, $availability_status, $capacity, $amenities, $image_name);

        if ($stmt->execute()) {
            $success_msg = "Room added successfully!";
        } else {
            $error_msg = "Failed to add room. Please try again.";
        }
        $stmt->close();
    }
}

// Handle room update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_room'])) {
    $room_id = $_POST['room_id'];
    $room_type = trim($_POST['room_type']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $availability_status = $_POST['availability_status'];
    $capacity = $_POST['capacity'];
    $amenities = trim($_POST['amenities']);
    $existing_image = $_POST['existing_image'];

    $image_name = $existing_image ?: $default_image;

    if (!empty($_FILES['room_image']['name'])) {
        $image_extension = strtolower(pathinfo($_FILES['room_image']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_extension, $allowed_types)) {
            $new_image_name = time() . '_' . basename($_FILES['room_image']['name']);
            $image_target = "../uploads/rooms/" . $new_image_name;

            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $image_target)) {
                if ($existing_image !== $default_image && file_exists("../" . $existing_image)) {
                    unlink("../" . $existing_image);
                }
                $image_name = "uploads/rooms/" . $new_image_name;
            } else {
                $error_msg = "Failed to upload new image.";
            }
        } else {
            $error_msg = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        }
    }

    if (empty($error_msg)) {
        $stmt = $conn->prepare("UPDATE rooms SET room_type = ?, price = ?, description = ?, availability_status = ?, capacity = ?, amenities = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdssissi", $room_type, $price, $description, $availability_status, $capacity, $amenities, $image_name, $room_id);

        if ($stmt->execute()) {
            $success_msg = "Room updated successfully!";
        } else {
            $error_msg = "Failed to update room.";
        }
        $stmt->close();
    }
}

// Handle room deletion
if (isset($_GET['delete_id'])) {
    $room_id = $_GET['delete_id'];

    $stmt = $conn->prepare("SELECT image FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $stmt->bind_result($room_image);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    if ($stmt->execute()) {
        if ($room_image !== $default_image && file_exists("../" . $room_image)) {
            unlink("../" . $room_image);
        }
        $success_msg = "Room deleted successfully!";
    } else {
        $error_msg = "Failed to delete room.";
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - YOUR HOTEL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        h1,
        h2 {
            text-align: center;
            color: #f5a623;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background: #4caf50;
            color: white;
        }

        .error {
            background: #f44336;
            color: white;
        }

        .room-form,
        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #3b3b3b;
            padding: 15px;
            border-radius: 8px;
        }

        .form-group {
            display: flex;
            gap: 10px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            background: #444;
            color: white;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .primary {
            background: #f5a623;
            color: white;
        }

        .edit-btn {
            background: #2196f3;
            color: white;
        }

        .delete-btn {
            background: #e53935;
            color: white;
        }

        .cancel-btn {
            background: #777;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #333;
        }

        .room-img {
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .room-img:hover {
            transform: scale(1.1);
        }

        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(145deg, #2e323d, #404552);
            color: #a0aec0;
            border-radius: 10px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: linear-gradient(145deg, #404552, #2e323d);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Rooms</h1>

        <?php if (!empty($success_msg)): ?>
            <p class="message success"><?= htmlspecialchars($success_msg); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <p class="message error"><?= htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <!-- Add Room Form -->
        <form method="POST" enctype="multipart/form-data" class="room-form">
            <h2>Add Room</h2>
            <div class="form-group">
                <input type="text" name="room_type" placeholder="Room Type" required>
                <input type="number" name="price" placeholder="Price per Night" step="0.01" required>
            </div>
            <textarea name="description" placeholder="Room Description" rows="4" required></textarea>
            <div class="form-group">
                <select name="availability_status" required>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                    <option value="maintenance">Maintenance</option>
                </select>
                <input type="number" name="capacity" placeholder="Capacity (No. of People)" min="1" required>
            </div>
            <textarea name="amenities" placeholder="Amenities (e.g., WiFi, AC, TV)" rows="2"></textarea>
            <label>Room Image:</label>
            <input type="file" name="room_image" accept="image/*" required>
            <button type="submit" name="add_room" class="btn primary">Add Room</button>
        </form>

        <!-- Rooms Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Availability</th>
                    <th>Capacity</th>
                    <th>Amenities</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['room_type']); ?></td>
                        <td>$<?= number_format($row['price'], 2); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= ucfirst($row['availability_status']); ?></td>
                        <td><?= $row['capacity']; ?> People</td>
                        <td><?= htmlspecialchars($row['amenities'] ?: 'N/A'); ?></td>
                        <td>
                            <?php if (!empty($row['image']) && file_exists("../" . $row['image'])): ?>
                                <img src="<?= htmlspecialchars("../" . $row['image']); ?>" class="room-img" width="100"
                                    onerror="this.onerror=null; this.src='../uploads/rooms/default-room.jpeg'">
                            <?php else: ?>
                                <img src="../uploads/rooms/default-room.jpeg" class="room-img" width="100">
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn edit-btn" onclick="toggleEditForm(<?= $row['id']; ?>)">Edit</button>
                            <a href="?delete_id=<?= $row['id']; ?>" class="btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                        </td>
                    </tr>
                    <!-- Edit Room Form (Initially Hidden) -->
                    <tr id="edit-room-<?= $row['id']; ?>" class="edit-room-form" style="display: none;">
                        <td colspan="9">
                            <form method="POST" enctype="multipart/form-data" class="edit-form">
                                <input type="hidden" name="room_id" value="<?= $row['id']; ?>">
                                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($row['image']); ?>">
                                <div class="form-group">
                                    <input type="text" name="room_type" value="<?= htmlspecialchars($row['room_type']); ?>" required>
                                    <input type="number" name="price" value="<?= $row['price']; ?>" required>
                                </div>
                                <textarea name="description" rows="4" required><?= htmlspecialchars($row['description']); ?></textarea>
                                <div class="form-group">
                                    <select name="availability_status" required>
                                        <option value="available" <?= ($row['availability_status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="booked" <?= ($row['availability_status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                                        <option value="maintenance" <?= ($row['availability_status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                                    </select>
                                    <input type="number" name="capacity" value="<?= $row['capacity']; ?>" min="1" required>
                                </div>
                                <textarea name="amenities" rows="2"><?= htmlspecialchars($row['amenities']); ?></textarea>
                                <input type="file" name="room_image" accept="image/*">
                                <button type="submit" name="update_room" class="btn primary">Update</button>
                                <button type="button" class="btn cancel-btn" onclick="toggleEditForm(<?= $row['id']; ?>)">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br><br>
        <hr><br>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <script>
        function toggleEditForm(id) {
            var formRow = document.getElementById("edit-room-" + id);
            if (formRow.style.display === "none") {
                formRow.style.display = "table-row";
            } else {
                formRow.style.display = "none";
            }
        }
    </script>
</body>

</html>