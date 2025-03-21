<?php
session_start();
include('../config/db.php'); // Database connection

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle delete user request with CSRF protection
if (isset($_GET['delete_id'], $_GET['token'])) {
    $user_id = filter_var($_GET['delete_id'], FILTER_VALIDATE_INT);
    $token = $_GET['token'];

    if ($user_id && hash_equals($_SESSION['csrf_token'], $token)) {
        if ($_SESSION['user_id'] == $user_id) {
            $_SESSION['error_msg'] = "You cannot delete yourself!";
        } else {
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $_SESSION['success_msg'] = "User deleted successfully!";
            } else {
                $_SESSION['error_msg'] = "Error deleting user!";
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error_msg'] = "Invalid request!";
    }
    header("Location: manage_users.php");
    exit();
}

// Fetch users from the database
$query = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(145deg, #1a202c, #2d3748);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .container {
            background: linear-gradient(145deg, #2c2f3a, #3d404c);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 8px 8px 20px #121217, -8px -8px 20px #4b4f5c;
            max-width: 1000px;
            width: 100%;
            text-align: center;
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

        h1 {
            color: #a0aec0;
            animation: fadeIn 1s ease-in-out;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            color: #a0aec0;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            transition: background 0.3s;
        }

        table th {
            background: #3d404c;
        }

        table tr {
            transition: background 0.3s, transform 0.2s;
        }

        table tr:hover {
            background: #404552;
            transform: scale(1.02);
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        .delete-btn {
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            background: #e53e3e;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .delete-btn:hover {
            background: #c53030;
            transform: scale(1.1);
        }

        .back-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background: linear-gradient(145deg, #2e323d, #404552);
            color: #a0aec0;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .back-btn:hover {
            background: linear-gradient(145deg, #404552, #2e323d);
            transform: translateY(-3px);
            box-shadow: 0px 5px 15px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Users</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <img class="profile-img"
                                src="<?php echo !empty($row['profile_image']) ? '../uploads/profiles/' . htmlspecialchars($row['profile_image']) : '../uploads/profiles/default.png'; ?>"
                                alt="Profile">
                        </td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="active" <?php echo $row['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $row['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="banned" <?php echo $row['status'] == 'banned' ? 'selected' : ''; ?>>Banned</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br><br>
        <hr><br>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>

</html>