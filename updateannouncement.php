<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id      = $_POST['id'] ?? '';
$title   = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$target  = trim($_POST['target'] ?? 'both');

if ($id == "" || $title == "" || $message == "") {
    echo json_encode(["status" => false, "message" => "All fields required"]);
    exit;
}

$sql = "UPDATE announcements 
        SET title = ?, message = ?, target = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $title, $message, $target, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Announcement updated"]);
} else {
    echo json_encode(["status" => false, "message" => "Update failed"]);
}
?>
