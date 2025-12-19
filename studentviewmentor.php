<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* SQL query */
$sql = "SELECT 
            mr.roll_no,
            u.name,
            u.email,
            mr.mentorship_field,
            mr.working_hours,
            mr.mentorship_style,
            mr.status
        FROM mentor_requests mr
        INNER JOIN users u ON mr.roll_no = u.roll_no
        WHERE mr.status = 'approved'";

/* Execute */
$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "status" => false,
        "error" => $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "count" => count($data),
    "mentors" => $data
]);
?>
