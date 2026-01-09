<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT COUNT(*) AS total FROM events";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "count" => (int)$row['total']
]);
