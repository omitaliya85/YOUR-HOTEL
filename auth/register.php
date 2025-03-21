<?php
session_start([
    'use_strict_mode' => 1,
    'use_only_cookies' => 1,
    'cookie_lifetime' => 0,
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);

require_once "../config/db.php";

// CSRF Token Generation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Session expired. Please try again.";
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token

    // Sanitize and Validate Inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace("/[^0-9+\-() ]/", "", trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $status = 'active'; // Default status

    // Email Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Strong Password Validation
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)
    ) {
        $errors[] = "Password must be at least 8 characters long, contain 1 uppercase letter, 1 number, and 1 special character.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email is already registered.";
    }
    $stmt->close();

    // Profile Image Handling
    $profile_image = NULL;
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../uploads/profiles/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!getimagesize($_FILES["profile_image"]["tmp_name"])) {
            $errors[] = "File is not a valid image.";
        } elseif (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES["profile_image"]["size"] > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = "Image size must be under 2MB.";
        } elseif (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $errors[] = "Error uploading profile image.";
        } else {
            $profile_image = $image_name;
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, address, password, profile_image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $address, $hashed_password, $profile_image, $status);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }

    $_SESSION['error'] = htmlspecialchars(implode("<br>", $errors));
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | YOUR HOTEL</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">
    <script src="../assets/js/ui-effects.js" defer></script>
</head>

<body class="dark-theme">
    <div class="auth-container">
        <h2>Register at YOUR HOTEL</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_SESSION['error']); ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <div class="form-group">
                <label for="profile_image">Profile Image (Optional)</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
            </div>

            <button type="submit" class="btn">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>

</html>