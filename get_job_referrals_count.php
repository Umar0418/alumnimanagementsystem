<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors to prevent breaking JSON
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require "db.php";
ob_clean();

$email = $_POST['email'] ?? '';

if (empty($email)) {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Email required", "count" => 0]);
    exit;
}

// Count job referrals posted by this alumni
// Try with posted_by first, fall back to 0 if column doesn't exist
$count = 0;

$sql = "SELECT COUNT(*) as total FROM jobs WHERE posted_by = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = (int)($row['total'] ?? 0);
    $stmt->close();
}

ob_end_clean();
echo json_encode([
    "status" => true,
    "count" => $count
]);

$conn->close();
?>
