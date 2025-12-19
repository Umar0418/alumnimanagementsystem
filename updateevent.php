<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id          = $_POST['id'] ?? '';
$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$event_date  = $_POST['event_date'] ?? '';
$event_time  = $_POST['event_time'] ?? '';
$venue       = $_POST['venue'] ?? '';

if ($id == "") {
    echo json_encode(["status" => false, "message" => "Event ID missing"]);
    exit;
}

$sql = "UPDATE events SET
        title = ?,
        description = ?,
        event_date = ?,
        event_time = ?,
        venue = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssi",
    $title,
    $description,
    $event_date,
    $event_time,
    $venue,
    $id
);

$stmt->execute();

/* 🔴 KEY LINE */
if ($stmt->affected_rows > 0) {
    echo json_encode([
        "status" => true,
        "message" => "Event updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No event updated (check ID or values)"
    ]);
}
?>
