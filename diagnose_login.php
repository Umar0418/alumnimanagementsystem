<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

if (ob_get_level()) ob_clean();

$response = array();

// Test 1: Check admin with plain text password
$response['test1'] = "Testing admin login with plain text password";
$test_email = "admin@gmail.com";
$test_password = "12345";

$sql = "SELECT id, email, password FROM admins WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $test_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $response['admin_found'] = true;
    $response['admin_id'] = $admin['id'];
    $response['admin_email'] = $admin['email'];
    $response['password_in_db'] = substr($admin['password'], 0, 50);
    $response['password_is_hashed'] = (substr($admin['password'], 0, 4) === '$2y$');
    $response['password_matches'] = ($admin['password'] === $test_password);
} else {
    $response['admin_found'] = false;
    $response['message'] = "No admin found with email: $test_email";
}
$stmt->close();

// Test 2: Check user login
$test_user_email = "dhatchu@gmail.com";
$test_user_password = "123";

$sql = "SELECT id, email, password, usertype FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $test_user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $response['user_found'] = true;
    $response['user_id'] = $user['id'];
    $response['user_email'] = $user['email'];
    $response['user_type'] = $user['usertype'];
    $response['user_password_in_db'] = $user['password'];
    $response['user_password_matches'] = ($user['password'] === $test_user_password);
} else {
    $response['user_found'] = false;
}
$stmt->close();

// Test 3: List all admins
$sql = "SELECT id, email, LEFT(password, 20) as pwd_preview FROM admins LIMIT 5";
$result = $conn->query($sql);
$response['all_admins'] = [];
while ($row = $result->fetch_assoc()) {
    $response['all_admins'][] = [
        'id' => $row['id'],
        'email' => $row['email'],
        'password_preview' => $row['pwd_preview']
    ];
}

$conn->close();

echo json_encode($response, JSON_PRETTY_PRINT);
?>
