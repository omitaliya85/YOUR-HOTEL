<?php
session_start([
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'cookie_lifetime' => 0,
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
]);

require_once "../config/db.php";

// Redirect to homepage if already logged in
if (!empty($_SESSION['user_id'])) {
    header("Location: ../pages/index.php", true, 302);
    exit();
}

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =================== Login Handler ===================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Session expired. Please try again.";
        header("Location: login.php");
        exit();
    }

    // Regenerate CSRF token after every POST to avoid reuse
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $_SESSION['error'] = "Invalid email format or password length.";
        header("Location: login.php");
        exit();
    }

    // Prepare query to fetch user data
    $stmt = $conn->prepare("SELECT id, username, password_hash, status FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Something went wrong. Please try again later.";
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $status);
        $stmt->fetch();

        if ($status === 'banned') {
            $_SESSION['error'] = "Your account has been banned.";
        } elseif ($status === 'inactive') {
            $_SESSION['error'] = "Your account is inactive. Please contact support.";
        } elseif (password_verify($password, $hashed_password)) {
            // Update last login and reset session
            $stmt_update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt_update->bind_param("i", $id);
            $stmt_update->execute();
            $stmt_update->close();

            // Set session and regenerate ID for security
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $username;

            header("Location: ../pages/index.php", true, 302);
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
    }
    $stmt->close();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | YOUR HOTEL</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-theme.css">
    <script src="../assets/js/ui-effects.js" defer></script>
</head>

<body class="dark-theme">
    <div class="auth-container">
        <h2>Login to <span class="highlight-text">YOUR HOTEL</span></h2>

        <?php if (!empty($_SESSION['error'])) : ?>
            <p class="error-msg"><?= htmlspecialchars($_SESSION['error']); ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" id="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn login-btn">Login</button>
        </form>

        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>