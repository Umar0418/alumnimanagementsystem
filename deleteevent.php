<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id = $_POST['id'] ?? '';

if ($id == "") {
    echo json_encode(["status" => false, "message" => "Event ID required"]);
    exit;
}

$sql = "DELETE FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Event deleted successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Delete failed"]);
}
?>
