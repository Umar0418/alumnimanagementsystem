<?php
header("Content-Type: application/json");
require "db.php";

/* alumni roll number */
$roll_no = isset($_POST['roll_no']) ? $_POST['roll_no'] : '';

if ($roll_no == '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* view mentor request status */
$sql = "SELECT 
            mentorship_field,
            working_hours,
            mentorship_style,
            status
        FROM mentor_requests
        WHERE roll_no = ?";

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
    "mentor_requests" => $data
]);
?>
