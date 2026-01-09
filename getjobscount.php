<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT COUNT(*) AS total FROM jobs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "count" => (int)$row['total']
]);
