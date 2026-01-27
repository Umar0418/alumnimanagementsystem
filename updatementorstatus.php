<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

/* Inputs - now using id for unique identification */
$id = $_POST['id'] ?? '';
$roll_no = $_POST['roll_no'] ?? ''; // Keep for backward compatibility
$status  = $_POST['status'] ?? '';

/* Validation - now accepts pending, approved, rejected */
if (($id == "" && $roll_no == "") || ($status!="approved" && $status!="rejected" && $status!="pending")) {
    echo json_encode([
        "status" => false,
        "message" => "Valid id or roll_no and status (pending/approved/rejected) required"
    ]);
    exit;
}

/* Update status - prefer id if provided, fall back to roll_no */
if ($id != "") {
    $sql = "UPDATE mentor_requests SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
} else {
    $sql = "UPDATE mentor_requests SET status=? WHERE roll_no=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $roll_no);
}
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "status" => true,
        "message" => "Mentor status updated to " . ucfirst($status),
        "roll_no" => $roll_no,
        "new_status" => $status
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No mentor request found for this roll number or status already set"
    ]);
}

$stmt->close();
$conn->close();
?>
