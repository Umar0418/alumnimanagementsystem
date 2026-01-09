<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

$sql = "SELECT COUNT(*) AS total FROM jobs";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "count" => (int)$row['total']
]);
?>
