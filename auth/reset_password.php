<?php
session_start();
require_once "../config/db.php";

// Redirect if no token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error'] = "Invalid or expired reset link.";
    header("Location: forgot_password.php");
    exit();
}

// Generate CSRF Token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Session expired. Try again.";
        header("Location: reset_password.php?token=" . urlencode($_POST['token']));
        exit();
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Refresh CSRF token

    $token = trim($_POST['token']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Password validation
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = "Password must be at least 8 characters, include an uppercase letter, and a number.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Validate the token
        $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $reset_data = $result->fetch_assoc();
        $stmt->close();

        if ($reset_data) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update user password
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $reset_data['email']);
            $stmt->execute();
            $stmt->close();

            // Remove token after reset
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->bind_param("s", $reset_data['email']);
            $stmt->execute();
            $stmt->close();

            $_SESSION['success'] = "Password has been reset successfully.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Invalid or expired reset link.";
        }
    }

    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: reset_password.php?token=" . urlencode($token));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | YOUR HOTEL</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">
    <script src="../assets/js/ui-effects.js" defer></script>
</head>

<body class="dark-theme">
    <div class="auth-container">
        <h2>Reset Password</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_SESSION['error']); ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="success-msg"><?= htmlspecialchars($_SESSION['success']); ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? ''); ?>">

            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Reset Password</button>
        </form>

        <p>Remembered your password? <a href="login.php">Login</a></p>
    </div>
</body>

</html>