<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
require "db.php";

/* Read raw JSON input */
$raw = file_get_contents("php://input");
if (!$raw) {
    echo json_encode(["status"=>false,"message"=>"No input received"]);
    exit;
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(["status"=>false,"message"=>"Invalid JSON"]);
    exit;
}

$email = trim($data['email'] ?? '');
if ($email === '') {
    echo json_encode(["status"=>false,"message"=>"Email required"]);
    exit;
}

/* Check user exists */
$stmt = $conn->prepare("SELECT roll_no FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status"=>false,"message"=>"Email not registered"]);
    exit;
}

$user = $res->fetch_assoc();
$roll_no = $user['roll_no'];

/* Generate token */
$token  = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

/* Save token */
$insert = $conn->prepare("
    INSERT INTO password_resets (roll_no, email, reset_token, expires_at)
    VALUES (?, ?, ?, ?)
");
$insert->bind_param("ssss", $roll_no, $email, $token, $expiry);
$insert->execute();

/* Send email (basic mail) */
$link = "http://localhost/alumni_api/reset_password.php?token=$token";
mail(
    $email,
    "Password Reset",
    "Click this link to reset your password:\n$link\n\nValid for 15 minutes.",
    "From: noreply@college.com"
);

echo json_encode([
    "status" => true,
    "message" => "Reset link sent to email"
]);
