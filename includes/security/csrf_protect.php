<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not available
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to generate CSRF input field
function csrf_token_field()
{
    $token = $_SESSION['csrf_token'] ?? '';
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Function to validate CSRF token
function validate_csrf_token($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        // Unset token after use
        unset($_SESSION['csrf_token']);
        die("CSRF token validation failed. Please try again.");
    }
}
