<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$sql = "SELECT * FROM funds ORDER BY created_at DESC";
$result = $conn->query($sql);

$funds = [];
while ($row = $result->fetch_assoc()) {
    $funds[] = $row;
}

echo json_encode([
    "status" => true,
    "funds" => $funds
]);
?>
