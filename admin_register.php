<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Get form-data */
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');
$usertype = "admin";

/* Validation */
if ($name == "" || $email == "" || $phone == "" || $password == "") {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

/* Check if email already exists */
$checkSql = "SELECT id FROM users WHERE email = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $checkStmt->close();
    echo json_encode([
        "status" => false,
        "message" => "An account with this email already exists"
    ]);
    exit;
}
$checkStmt->close();

/* Insert into USERS table */
$sql = "INSERT INTO users (name, email, phone, password, usertype) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("sssss", $name, $email, $phone, $password, $usertype);

if ($stmt->execute()) {
    $stmt->close();
    echo json_encode([
        "status" => true,
        "message" => "Admin account created successfully! Please login."
    ]);
} else {
    $error = $stmt->error;
    $stmt->close();
    echo json_encode([
        "status" => false,
        "message" => "Registration failed: " . $error
    ]);
}

$conn->close();
?>
