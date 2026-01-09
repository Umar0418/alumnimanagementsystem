<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email required", "count" => 0]);
    exit;
}

// Count mentees for this mentor (approved mentorship requests)
$sql = "SELECT COUNT(*) as total FROM mentor_requests WHERE mentor_email = ? AND status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$count = (int)($row['total'] ?? 0);

echo json_encode([
    "status" => true,
    "count" => $count
]);

$stmt->close();
$conn->close();
?>
