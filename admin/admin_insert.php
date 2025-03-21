<?php
include('../config/db.php'); // Database connection

// Securely define admin credentials
$username = 'om';
$password = 'om123';

// Check if admin already exists
$checkQuery = "SELECT id FROM admin WHERE username = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "Admin already exists!";
} else {
    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin
    $insertQuery = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ss", $username, $hashedPassword);

    if ($insertStmt->execute()) {
        echo "Admin inserted successfully!";
    } else {
        echo "Error inserting admin: " . $conn->error;
    }

    $insertStmt->close();
}

$checkStmt->close();
$conn->close();
