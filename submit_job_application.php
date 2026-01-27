<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffer
ob_start();

require "db.php";
ob_clean();

// Get form data
$roll_no = $_POST['roll_no'] ?? '';
$job_id = $_POST['job_id'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$skills = $_POST['skills'] ?? '';
$experience = $_POST['experience'] ?? '';
$current_company = $_POST['current_company'] ?? '';
$linkedin = $_POST['linkedin'] ?? '';
$cover_letter = $_POST['cover_letter'] ?? '';
$expected_salary = $_POST['expected_salary'] ?? '';

error_log("=== Submit Job Application ===");
error_log("Roll No: $roll_no");
error_log("Job ID: $job_id");
error_log("Full Name: $full_name");
error_log("Email: $email");
error_log("Phone: $phone");

// Validation
if ($full_name == "" || $email == "" || $phone == "" || $skills == "") {
    error_log("ERROR: Missing required fields");
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "Please fill all required fields"
    ]);
    exit;
}

// Check if already applied
error_log("Checking for existing application...");
error_log("Query params - Roll No: '$roll_no', Job ID: '$job_id'");

$checkExisting = $conn->prepare(
    "SELECT id, roll_no, job_id, full_name, created_at FROM job_applications WHERE roll_no=? AND job_id=?"
);
$checkExisting->bind_param("ss", $roll_no, $job_id);
$checkExisting->execute();
$result = $checkExisting->get_result();

error_log("Existing applications found: " . $result->num_rows);

if ($result->num_rows > 0) {
    // Log the found records
    while ($row = $result->fetch_assoc()) {
        error_log("Found existing application:");
        error_log("  ID: " . $row['id']);
        error_log("  Roll No: '" . $row['roll_no'] . "'");
        error_log("  Job ID: '" . $row['job_id'] . "'");
        error_log("  Name: " . $row['full_name']);
        error_log("  Created: " . $row['created_at']);
    }
    
    $checkExisting->close();
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "You have already applied for this job"
    ]);
    exit;
}
$checkExisting->close();

// Insert application
error_log("Inserting new application...");
$sql = "INSERT INTO job_applications 
        (roll_no, job_id, full_name, email, phone, skills, experience, current_company, linkedin, cover_letter, expected_salary, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("sssssssssss", 
    $roll_no, $job_id, $full_name, $email, $phone, 
    $skills, $experience, $current_company, $linkedin, 
    $cover_letter, $expected_salary
);

if ($stmt->execute()) {
    $inserted_id = $stmt->insert_id;
    error_log("Application inserted successfully with ID: $inserted_id");
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "message" => "Application submitted successfully!",
        "application_id" => $inserted_id
    ]);
} else {
    error_log("Insert failed: " . $stmt->error);
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "Failed to submit application: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
