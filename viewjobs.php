<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$sql = "SELECT * FROM jobs ORDER BY created_at DESC";
$result = $conn->query($sql);

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

echo json_encode([
    "status" => true,
    "jobs" => $jobs
]);
?>
