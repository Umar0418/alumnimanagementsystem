<?php
header("Content-Type: application/json");

// Test database connection
require "db.php";

$response = array();
$response['db_connection'] = $conn ? "Connected" : "Failed";

// Test admin login endpoint
$admin_email = "test@admin.com";
$admin_password = "password";

$sql = "SELECT * FROM admins WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();
$response['admin_query'] = $result->num_rows > 0 ? "Found admin" : "No admin found";
$stmt->close();

// Test users login endpoint
$user_email = "test@student.com";
$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$response['user_query'] = $result->num_rows > 0 ? "Found user" : "No user found";
$stmt->close();

// Check if files exist
$response['admin_login_exists'] = file_exists(__DIR__ . '/admin_login.php') ? "Yes" : "No";
$response['login_exists'] = file_exists(__DIR__ . '/login.php') ? "Yes" : "No";
$response['student_register_exists'] = file_exists(__DIR__ . '/student_register.php') ? "Yes" : "No";

$conn->close();

echo json_encode($response, JSON_PRETTY_PRINT);
?>
