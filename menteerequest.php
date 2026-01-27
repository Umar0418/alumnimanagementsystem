<?php
header("Content-Type: application/json");
require "db.php";

/* Get form-data */
$roll_no        = $_POST['roll_no'] ?? '';        // student roll number
$mentor_roll_no = $_POST['mentor_roll_no'] ?? ''; // alumni roll number

/* Validation */
if ($roll_no=="" || $mentor_roll_no=="") {
    echo json_encode([
        "status" => false,
        "message" => "Roll numbers required"
    ]);
    exit;
}

/* Verify STUDENT exists */
$checkStudent = $conn->prepare(
    "SELECT roll_no FROM users WHERE roll_no=? AND usertype='student'"
);
$checkStudent->bind_param("s", $roll_no);
$checkStudent->execute();
$checkStudent->store_result();

if ($checkStudent->num_rows == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Student not registered"
    ]);
    exit;
}

/* Verify ALUMNI exists */
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
    exit;
}


/* Insert mentee request */
$topic = $_POST['topic'] ?? 'General Mentorship';

$sql = "INSERT INTO mentee_requests
(roll_no, mentor_roll_no, topic, status)
VALUES (?, ?, ?, 'pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $roll_no, $mentor_roll_no, $topic);
$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Mentee request sent successfully",
    "request_status" => "pending"
]);
?>
