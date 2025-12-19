<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$title  = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$target = trim($_POST['target'] ?? 'both'); // students / alumni / both

if ($title == "" || $message == "") {
    echo json_encode(["status" => false, "message" => "Title and message are required"]);
    exit;
}

$sql = "INSERT INTO announcements (title, message, target)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $title, $message, $target);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Announcement posted successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to post announcement"]);
}
?>
