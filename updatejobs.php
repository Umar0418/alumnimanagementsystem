<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

// Check connection
if (!$conn) {
    echo json_encode(["status" => false, "message" => "Database connection failed"]);
    exit;
}

$id          = $_POST['id'] ?? '';
$title       = trim($_POST['title'] ?? '');
$company     = trim($_POST['company'] ?? '');
$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$job_type    = trim($_POST['job_type'] ?? '');
$salary      = trim($_POST['salary'] ?? '');
$last_date   = trim($_POST['last_date'] ?? '');

// Log received data for debugging
error_log("Update Job Request - ID: $id, Title: $title, Last Date: $last_date");

// Convert date from MM/dd/yyyy to YYYY-MM-DD for MySQL if needed
if (!empty($last_date) && $last_date !== '0000-00-00') {
    // Check if date is in MM/dd/yyyy format
    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $last_date, $matches)) {
        // Convert MM/dd/yyyy to YYYY-MM-DD
        $last_date = $matches[3] . '-' . $matches[1] . '-' . $matches[2];
        error_log("Converted date format to: $last_date");
    }
}

if ($id == "" || $title == "" || $company == "" || $job_type == "" || $salary == "" || $last_date == "") {
    echo json_encode(["status" => false, "message" => "All required fields must be provided"]);
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

if (!$stmt) {
    echo json_encode(["status" => false, "message" => "Failed to prepare statement: " . $conn->error]);
    exit;
}

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
    // Check if any row was actually updated
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status" => true,
            "message" => "Job updated successfully"
        ]);
    } else {
        // No rows updated - but this could mean data was same, still return success
        echo json_encode([
            "status" => true,
            "message" => "Job updated (no changes detected)"
        ]);
    }
} else {
    echo json_encode([
        "status" => false, 
        "message" => "Update failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
