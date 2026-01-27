<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors from appearing in output
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require "db.php";
ob_clean();

$title       = trim($_POST['title'] ?? '');
$company     = trim($_POST['company'] ?? '');
$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$job_type    = trim($_POST['job_type'] ?? 'Full-time');
$salary      = trim($_POST['salary'] ?? 'Not specified');
$last_date   = trim($_POST['last_date'] ?? '');
$roll_no     = trim($_POST['roll_no'] ?? ''); // Alumni who is posting the job

// Only require title and company
if ($title == "" || $company == "") {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Job title and company are required"]);
    exit;
}

// Set default last_date if empty (30 days from now)
if ($last_date == "") {
    $last_date = date('Y-m-d', strtotime('+30 days'));
}

// Set default job_type if empty
if ($job_type == "") {
    $job_type = "Full-time";
}

// Set default salary if empty
if ($salary == "") {
    $salary = "Not specified";
}

// Include posted_by in the INSERT query
$sql = "INSERT INTO jobs 
(title, company, description, location, job_type, salary, last_date, posted_by)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssss",
    $title,
    $company,
    $description,
    $location,
    $job_type,
    $salary,
    $last_date,
    $roll_no
);

if ($stmt->execute()) {
    $newId = $conn->insert_id;
    ob_end_clean();
    echo json_encode([
        "status" => true, 
        "message" => "Job added successfully",
        "job_id" => $newId
    ]);
} else {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Failed to add job: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
