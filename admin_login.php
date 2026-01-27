<?php
// Start output buffering to prevent any output before JSON response
ob_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to output

require "db.php";

/* Get form-data */
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

/* Debug: Log received data (remove in production) */
error_log("Admin Login - Received request for email: $email");

/* Validation */
if (empty($email) || empty($password)) {
    if (ob_get_level()) ob_clean();
    echo json_encode([
        "status" => false,
        "message" => "Email and password are required"
    ]);
    exit;
}

/* Query the admins table */
$sql = "SELECT id, name, email, phone, password, usertype, created_at FROM admins WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    if (ob_get_level()) ob_clean();
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    if (ob_get_level()) ob_clean();
    echo json_encode([
        "status" => false,
        "message" => "Invalid email or password"
    ]);
    exit;
}

$admin = $result->fetch_assoc();
$stmt->close();

/* Verify password - using plain text comparison for now */
/* TODO: In production, use password_hash() and password_verify() */
if ($password !== $admin['password']) {
    if (ob_get_level()) ob_clean();
    echo json_encode([
        "status" => false,
        "message" => "Invalid email or password"
    ]);
    exit;
}

/* Successful login - return admin data */
if (ob_get_level()) ob_clean();
echo json_encode([
    "status" => true,
    "message" => "Login successful",
    "admin" => [
        "id" => $admin['id'],
        "name" => $admin['name'],
        "email" => $admin['email'],
        "phone" => $admin['phone'],
        "usertype" => $admin['usertype'],
        "created_at" => $admin['created_at']
    ]
]);

$conn->close();
?>
