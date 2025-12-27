<?php
header("Content-Type: application/json");
require "db.php";

// This script fetches ALL announcements for the admin panel, with no parameters needed.
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);

$announcements = [];
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

echo json_encode([
    "status" => true,
    "announcements" => $announcements
]);
?>