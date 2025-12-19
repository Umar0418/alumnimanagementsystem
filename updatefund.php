<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id             = $_POST['id'] ?? '';
$fund_title     = trim($_POST['fund_title'] ?? '');
$description    = trim($_POST['description'] ?? '');
$target_amount  = trim($_POST['target_amount'] ?? '');
$collected_amt  = trim($_POST['collected_amount'] ?? '');
$last_date      = trim($_POST['last_date'] ?? '');

if ($id == "" || $fund_title == "" || $target_amount == "" || $last_date == "") {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit;
}

$sql = "UPDATE funds SET
        fund_title = ?,
        description = ?,
        target_amount = ?,
        collected_amount = ?,
        last_date = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssddsi",
    $fund_title,
    $description,
    $target_amount,
    $collected_amt,
    $last_date,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Fund updated successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Update failed"]);
}
?>
