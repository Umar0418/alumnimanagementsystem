<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

// Get form-data
$student_roll_no = isset($_POST['student_roll_no']) ? trim($_POST['student_roll_no']) : '';
$mentor_roll_no = isset($_POST['mentor_roll_no']) ? trim($_POST['mentor_roll_no']) : '';
$topic = isset($_POST['topic']) ? trim($_POST['topic']) : '';

// Validation
if ($student_roll_no === "" || $mentor_roll_no === "") {
    echo '{"status":false,"message":"Missing roll numbers"}';
    exit;
}

// Create mentee_requests table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS mentee_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50) NOT NULL,
    mentor_roll_no VARCHAR(50) NOT NULL,
    topic TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Insert mentorship request using simple query
$sql = "INSERT INTO mentee_requests (roll_no, mentor_roll_no, topic, status) 
        VALUES ('" . $conn->real_escape_string($student_roll_no) . "', 
                '" . $conn->real_escape_string($mentor_roll_no) . "', 
                '" . $conn->real_escape_string($topic) . "', 
                'pending')";

if ($conn->query($sql)) {
    echo '{"status":true,"message":"Mentorship request sent successfully!"}';
} else {
    echo '{"status":false,"message":"Database error"}';
}

$conn->close();
?>
