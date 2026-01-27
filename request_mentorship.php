<?php
// Start output buffering to catch any accidental output
ob_start();

// Suppress errors from being output (log them instead)
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

// Get form-data
$student_roll_no = isset($_POST['student_roll_no']) ? trim($_POST['student_roll_no']) : '';
$mentor_roll_no = isset($_POST['mentor_roll_no']) ? trim($_POST['mentor_roll_no']) : '';
$topic = isset($_POST['topic']) ? trim($_POST['topic']) : 'General Mentorship';

// Validation
if ($student_roll_no === "" || $mentor_roll_no === "") {
    echo json_encode([
        "status" => false,
        "message" => "Missing roll numbers"
    ]);
    exit;
}

// Verify STUDENT exists
$checkStudent = $conn->prepare(
    "SELECT roll_no FROM users WHERE roll_no=? AND usertype='student'"
);
$checkStudent->bind_param("s", $student_roll_no);
$checkStudent->execute();
$checkStudent->store_result();

if ($checkStudent->num_rows == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Student not registered"
    ]);
    $checkStudent->close();
    exit;
}
$checkStudent->close();

// Verify ALUMNI/MENTOR exists
$checkMentor = $conn->prepare(
    "SELECT roll_no FROM users WHERE roll_no=? AND usertype='alumni'"
);
$checkMentor->bind_param("s", $mentor_roll_no);
$checkMentor->execute();
$checkMentor->store_result();

if ($checkMentor->num_rows == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Mentor not registered"
    ]);
    $checkMentor->close();
    exit;
}
$checkMentor->close();

// Check if request already exists
$checkExisting = $conn->prepare(
    "SELECT id FROM mentee_requests WHERE roll_no=? AND mentor_roll_no=? AND status='pending'"
);
$checkExisting->bind_param("ss", $student_roll_no, $mentor_roll_no);
$checkExisting->execute();
$checkExisting->store_result();

if ($checkExisting->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "You already have a pending request with this mentor"
    ]);
    $checkExisting->close();
    exit;
}
$checkExisting->close();

// Insert mentorship request
$sql = "INSERT INTO mentee_requests (roll_no, mentor_roll_no, topic, status) 
        VALUES (?, ?, ?, 'pending')";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("sss", $student_roll_no, $mentor_roll_no, $topic);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Mentorship request sent successfully!",
        "request_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to send request: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
