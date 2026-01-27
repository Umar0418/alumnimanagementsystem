<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

// Test with real admin credentials from your database
$admin_email = "admin@test.com";
$admin_password = "12345";  // Try with plain text first

if (ob_get_level()) ob_clean();

$response = array();

// Test 1: Check if admin exists
$sql = "SELECT * FROM admins WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $response['admin_found'] = true;
    $response['admin_email'] = $admin['email'];
    $response['stored_password'] = substr($admin['password'], 0, 20) . "..."; // First 20 chars
    
    // Test password verification
    if ($admin['password'] === $admin_password) {
        $response['password_match'] = "Exact match (plain text)";
    } else if (password_verify($admin_password, $admin['password'])) {
        $response['password_match'] = "Hash verified";
    } else {
        $response['password_match'] = "No match";
    }
} else {
    $response['admin_found'] = false;
}
$stmt->close();

// Test 2: Check if user exists (student/alumni)
$user_email = "dhatchu@gmail.com";
$user_password = "123";

$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $response['user_found'] = true;
    $response['user_email'] = $user['email'];
    $response['user_type'] = $user['usertype'];
    $response['user_stored_password'] = substr($user['password'], 0, 20) . "...";
    
    // Test password verification
    if ($user['password'] === $user_password) {
        $response['user_password_match'] = "Exact match (plain text)";
    } else if (password_verify($user_password, $user['password'])) {
        $response['user_password_match'] = "Hash verified";
    } else {
        $response['user_password_match'] = "No match";
    }
} else {
    $response['user_found'] = false;
}
$stmt->close();

$conn->close();

echo json_encode($response, JSON_PRETTY_PRINT);
?>
