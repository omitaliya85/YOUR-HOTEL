<?php
session_start();
require_once "../config/db.php";

// Generate CSRF Token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid session. Please try again.";
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Refresh token

    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Generate Reset Token
            $token = bin2hex(random_bytes(50));
            $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Insert or Update Token
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
            $stmt->bind_param("sssss", $email, $token, $expires_at, $token, $expires_at);
            $stmt->execute();
            $stmt->close();

            // **NO EMAIL SENT** - Just storing token
            $success = "If this email exists, a reset link has been generated. Please check your email.";
        } else {
            $errors[] = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password | YOUR HOTEL</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">
    <script src="../assets/js/ui-effects.js" defer></script>
</head>

<body class="dark-theme">
    <div class="auth-container">
        <h2>Forgot Password</h2>

        <?php if (!empty($errors)): ?>
            <p class="error-msg"><?= htmlspecialchars(implode("<br>", $errors)); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="success-msg"><?= htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <button type="submit" class="btn">Send Reset Link</button>
        </form>

        <p>Remember your password? <a href="login.php">Login</a></p>
    </div>
</body>

</html>