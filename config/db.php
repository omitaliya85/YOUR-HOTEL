<?php
// Load environment variables from .env file securely
$env = parse_ini_file(__DIR__ . '/../.env');

// Database Configuration
$servername = $env['DB_HOST'] ?? 'localhost';
$username   = $env['DB_USER'] ?? 'root';
$password   = $env['DB_PASS'] ?? '';
$database   = $env['DB_NAME'] ?? 'yourhoteldb';
$port       = $env['DB_PORT'] ?? 3306;

// Enable strict MySQLi error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Define log paths
$error_log_path = __DIR__ . '/../logs/db_errors.log';
$query_log_path = __DIR__ . '/../logs/db_queries.log';

try {
    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Set character set to UTF-8 for proper encoding
    $conn->set_charset("utf8mb4");

    // Enable MySQL strict mode for better security
    $conn->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

    // Function to log executed queries (for debugging)
    function log_query($query)
    {
        global $query_log_path;
        file_put_contents($query_log_path, "[" . date('Y-m-d H:i:s') . "] Query: $query\n", FILE_APPEND);
    }
} catch (mysqli_sql_exception $e) {
    // Log database connection errors
    error_log("[" . date('Y-m-d H:i:s') . "] Database Connection Error: " . $e->getMessage() . "\n", 3, $error_log_path);

    // Hide error details from users in production
    die("Database connection failed! Please try again later.");
}

// =========================
// PDO Fallback (Optional)
// =========================
try {
    if (!isset($conn) || $conn->connect_error) {
        $dsn = "mysql:host=$servername;dbname=$database;charset=utf8mb4;port=$port";
        $pdo_conn = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
} catch (PDOException $e) {
    // Log PDO errors if MySQLi fails
    error_log("[" . date('Y-m-d H:i:s') . "] PDO Fallback Error: " . $e->getMessage() . "\n", 3, $error_log_path);

    // Hide PDO error details from users
    die("Database connection failed! Please try again later.");
}
