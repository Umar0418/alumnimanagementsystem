<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Get form-data */
$roll_no           = $_POST['roll_no'] ?? '';
$mentorship_field  = $_POST['mentorship_field'] ?? '';
$working_hours     = $_POST['working_hours'] ?? '';
$mentorship_style  = $_POST['mentorship_style'] ?? '';

/* Default status */
$status = "pending";

/* Validation */
if (
    $roll_no=="" || 
    $mentorship_field=="" || 
    $working_hours=="" || 
    $mentorship_style==""
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

/* Check alumni exists */
$check = $conn->prepare(
    "SELECT roll_no FROM users WHERE roll_no=? AND usertype='alumni'"
);
$check->bind_param("s", $roll_no);
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Alumni not registered"
    ]);
    exit;
}

/* Insert mentor request */
$sql = "INSERT INTO mentor_requests
(roll_no, mentorship_field, working_hours, mentorship_style, status)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssss",
    $roll_no,
    $mentorship_field,
    $working_hours,
    $mentorship_style,
    $status
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Mentor request sent successfully",
    "request_status" => "pending"
]);
?>
