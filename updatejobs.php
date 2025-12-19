<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id          = $_POST['id'] ?? '';
$title       = trim($_POST['title'] ?? '');
$company     = trim($_POST['company'] ?? '');
$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$job_type    = trim($_POST['job_type'] ?? '');
$salary      = trim($_POST['salary'] ?? '');
$last_date   = trim($_POST['last_date'] ?? '');

if ($id == "" || $title == "" || $company == "" || $job_type == "" || $salary == "" || $last_date == "") {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit;
}

$sql = "UPDATE jobs SET
        title = ?,
        company = ?,
        description = ?,
        location = ?,
        job_type = ?,
        salary = ?,
        last_date = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssi",
    $title,
    $company,
    $description,
    $location,
    $job_type,
    $salary,
    $last_date,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Job updated successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Update failed"]);
}
?>
