<?php
header("Content-Type: application/json");
require "db.php";

/* Read form-data or JSON */
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    $data = $_POST;
}

$roll_no = $data['roll_no'] ?? null;

if (!$roll_no) {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* Check mentorship status */
$sql = "SELECT status FROM mentor_requests WHERE roll_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roll_no);
$stmt->execute();
$res = $stmt->get_result();

/* Not applied */
if ($res->num_rows === 0) {
    echo json_encode([
        "status" => true,
        "action" => "apply"
    ]);
    exit;
}

/* Applied */
$row = $res->fetch_assoc();
echo json_encode([
    "status" => true,
    "mentor_status" => $row['status']
]);
