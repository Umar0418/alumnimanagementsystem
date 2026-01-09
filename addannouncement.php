<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

// Check if connection is valid
if (!$conn) {
    echo json_encode(["status" => false, "message" => "Database connection failed"]);
    exit;
}

$title  = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$target = trim($_POST['target'] ?? 'both'); // students / alumni / both

if ($title == "" || $message == "") {
    echo json_encode(["status" => false, "message" => "Title and message are required"]);
    exit;
}

$sql = "INSERT INTO announcements (title, message, target) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => false, "message" => "Failed to prepare statement: " . $conn->error]);
    exit;
}

$stmt->bind_param("sss", $title, $message, $target);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Announcement posted successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to post announcement: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
