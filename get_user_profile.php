<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require "db.php";
ob_clean();

/* Get roll_no from request */
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    $data = $_POST;
}

$roll_no = trim($data['roll_no'] ?? '');

if ($roll_no === '') {
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* Get user data from users table */
$sql = "SELECT roll_no, name, email, phone, department, address, cgpa, interests, degree, batch_number
        FROM users WHERE roll_no = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    // Fallback if new columns don't exist
    $sql = "SELECT roll_no, name, email, phone, department, address, cgpa, interests
            FROM users WHERE roll_no = ?";
    $stmt = $conn->prepare($sql);
}

$stmt->bind_param("s", $roll_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Ensure all fields exist in response
    $user['degree'] = $user['degree'] ?? '';
    $user['batch_number'] = $user['batch_number'] ?? '';
    $user['address'] = $user['address'] ?? '';
    $user['cgpa'] = $user['cgpa'] ?? '';
    $user['interests'] = $user['interests'] ?? '';
    
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "user" => $user
    ]);
} else {
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "User not found"
    ]);
}

$stmt->close();
$conn->close();
?>
