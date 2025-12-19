<?php
header("Content-Type: application/json");
require "db.php";

/* student roll number */
$roll_no = isset($_POST['roll_no']) ? $_POST['roll_no'] : '';

if ($roll_no == '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* view mentee request status */
$sql = "SELECT 
            mr.mentor_roll_no,
            u.name AS mentor_name,
            mr.status
        FROM mentee_requests mr
        JOIN users u ON mr.mentor_roll_no = u.roll_no
        WHERE mr.roll_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roll_no);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "requests" => $data
]);
?>
