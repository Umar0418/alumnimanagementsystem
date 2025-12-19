<?php
header("Content-Type: application/json");
require "db.php";

/* Inputs */
$roll_no = $_POST['roll_no'] ?? '';
$status  = $_POST['status'] ?? '';

/* Validation */
if ($roll_no=="" || ($status!="approved" && $status!="rejected")) {
    echo json_encode([
        "status" => false,
        "message" => "Valid roll_no and status required"
    ]);
    exit;
}

/* Update status using roll number */
$sql = "UPDATE mentor_requests
        SET status=?
        WHERE roll_no=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $status, $roll_no);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "status" => true,
        "message" => "Mentor request updated successfully",
        "roll_no" => $roll_no,
        "new_status" => $status
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No mentor request found for this roll number or status already updated"
    ]);
}
?>
