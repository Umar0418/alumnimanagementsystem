<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$fund_title    = trim($_POST['fund_title'] ?? '');
$description   = trim($_POST['description'] ?? '');
$target_amount = trim($_POST['target_amount'] ?? '');
$last_date     = trim($_POST['last_date'] ?? '');

if ($fund_title == "" || $target_amount == "" || $last_date == "") {
    echo json_encode(["status" => false, "message" => "Required fields missing"]);
    exit;
}

$sql = "INSERT INTO funds (fund_title, description, target_amount, last_date)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssds", $fund_title, $description, $target_amount, $last_date);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Fund created successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to create fund"]);
}
?>
