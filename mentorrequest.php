<?php
header("Content-Type: application/json");
require "db.php";

// Get form-data
$student_roll_no = $_POST['student_roll_no'] ?? '';
$mentor_roll_no = $_POST['mentor_roll_no'] ?? '';
$topic = $_POST['topic'] ?? '';

// Validation
if ($student_roll_no == "" || $mentor_roll_no == "") {
    echo json_encode([
        "status" => false,
        "message" => "Roll numbers required"
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
    exit;
}

// Verify MENTOR exists and is approved
$checkMentor = $conn->prepare(
    "SELECT roll_no FROM mentor_requests WHERE roll_no=? AND status='approved'"
);
$checkMentor->bind_param("s", $mentor_roll_no);
$checkMentor->execute();
$checkMentor->store_result();

if ($checkMentor->num_rows == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Mentor not available"
    ]);
    exit;
}

// Check if already requested
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
    exit;
}

// Insert mentorship request
$sql = "INSERT INTO mentee_requests (roll_no, mentor_roll_no, topic, status, created_at) 
        VALUES (?, ?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $student_roll_no, $mentor_roll_no, $topic);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Mentorship request sent successfully!"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to send request: " . $conn->error
    ]);
}

$conn->close();
?>
