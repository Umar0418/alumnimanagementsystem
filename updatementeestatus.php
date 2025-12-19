<?php
header("Content-Type: application/json");
require "db.php";

$roll_no        = $_POST['roll_no'] ?? '';        // student
$mentor_roll_no = $_POST['mentor_roll_no'] ?? '';
$status         = $_POST['status'] ?? '';

if (
    $roll_no=="" || $mentor_roll_no=="" ||
    ($status!="approved" && $status!="rejected")
) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid input"
    ]);
    exit;
}

$sql = "UPDATE mentee_requests
        SET status=?
        WHERE roll_no=? AND mentor_roll_no=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $status, $roll_no, $mentor_roll_no);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "status" => true,
        "message" => "Mentee request updated",
        "new_status" => $status
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No request found"
    ]);
}
?>
