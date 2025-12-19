<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$event_date  = trim($_POST['event_date'] ?? '');
$event_time  = trim($_POST['event_time'] ?? '');
$venue       = trim($_POST['venue'] ?? '');

if ($title == "" || $event_date == "" || $event_time == "" || $venue == "") {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit;
}

$sql = "INSERT INTO events (title, description, event_date, event_time, venue)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $description, $event_date, $event_time, $venue);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Event added successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to add event"]);
}
?>
