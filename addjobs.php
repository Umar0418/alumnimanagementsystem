<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$title       = trim($_POST['title'] ?? '');
$company     = trim($_POST['company'] ?? '');
$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$job_type    = trim($_POST['job_type'] ?? '');
$salary      = trim($_POST['salary'] ?? '');
$last_date   = trim($_POST['last_date'] ?? '');

if ($title == "" || $company == "" || $job_type == "" || $salary == "" || $last_date == "") {
    echo json_encode(["status" => false, "message" => "Required fields missing"]);
    exit;
}

$sql = "INSERT INTO jobs 
(title, company, description, location, job_type, salary, last_date)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $title,
    $company,
    $description,
    $location,
    $job_type,
    $salary,
    $last_date
);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Job added successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to add job"]);
}
?>
