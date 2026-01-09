<?php
header("Content-Type: application/json");
require "db.php";

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

// Validation
if ($full_name == "" || $email == "" || $phone == "" || $skills == "") {
    echo json_encode([
        "status" => false,
        "message" => "Please fill all required fields"
    ]);
    exit;
}

// Check if already applied
$checkExisting = $conn->prepare(
    "SELECT id FROM job_applications WHERE roll_no=? AND job_id=?"
);
$checkExisting->bind_param("ss", $roll_no, $job_id);
$checkExisting->execute();
$checkExisting->store_result();

if ($checkExisting->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "You have already applied for this job"
    ]);
    exit;
}

// Insert application
$sql = "INSERT INTO job_applications 
        (roll_no, job_id, full_name, email, phone, skills, experience, current_company, linkedin, cover_letter, expected_salary, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss", 
    $roll_no, $job_id, $full_name, $email, $phone, 
    $skills, $experience, $current_company, $linkedin, 
    $cover_letter, $expected_salary
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Application submitted successfully!"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to submit application: " . $conn->error
    ]);
}

$conn->close();
?>
