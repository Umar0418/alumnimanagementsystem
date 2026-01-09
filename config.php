<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Use your existing database file
include 'db.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email is required"]);
    exit;
}

// Check if email exists in alumni table
$stmt = $conn->prepare("SELECT * FROM alumni WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists - in production, send actual email here
    echo json_encode(["status" => true, "message" => "Reset link sent to your email"]);
} else {
    echo json_encode(["status" => false, "message" => "Email not found in our records"]);
}

$conn->close();
?>