<?php
session_start();
include '../config/db.php';

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");

// Rate limiting (Brute-force protection)
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 5) {
    die("Too many failed attempts. Please try again later.");
}

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['login_attempts'] = 0; // Reset failed attempts
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $_SESSION['login_attempts']++;
                $error = "Invalid username or password!";
            }
        } else {
            $_SESSION['login_attempts']++;
            $error = "Admin not found!";
        }
        $stmt->close();
    } else {
        $error = "Please enter both username and password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - YOUR HOTEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(120deg, rgb(79, 86, 16), rgb(9, 90, 231), rgb(216, 72, 0));
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: 300% 300%;
            animation: gradientAnimation 6s ease infinite;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-container {
            background: linear-gradient(145deg, #2c2f3a, #3d404c);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 8px 8px 20px #121217, -8px -8px 20px #4b4f5c;
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeInScale 1.5s ease-out;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-container h2 {
            margin-bottom: 1rem;
            color: #a0aec0;
            text-shadow: 0px 4px 4px rgba(160, 174, 192, 0.8);
        }

        .login-container .error {
            color: #f56565;
            background: rgba(255, 0, 0, 0.1);
            padding: 0.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .login-container input {
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            background: #2e323d;
            color: #a0aec0;
            font-size: 1rem;
            box-shadow: inset 4px 4px 10px #1c1e26, inset -4px -4px 10px #555a66;
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            outline: none;
            background: #3a3f4a;
        }

        .login-container button {
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(145deg, #2e323d, #404552);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 15px rgba(160, 174, 192, 0.8), -4px -4px 15px rgba(160, 174, 192, 0.8);
        }

        .login-container button:hover {
            background: linear-gradient(145deg, #404552, #2e323d);
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>ADMIN LOGIN</h2>
        <?php if (isset($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required autocomplete="off">
            <button type="submit">LOGIN</button>
        </form>
    </div>
</body>

</html>