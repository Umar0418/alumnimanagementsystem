<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

$roll_no = $_POST['roll_no'] ?? null;

if (!$roll_no) {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

// Check mentor_requests table - THIS IS THE SAME TABLE THAT ADMIN UPDATES
$sql = "SELECT status, mentorship_field, working_hours, mentorship_style FROM mentor_requests WHERE roll_no = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $roll_no);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo json_encode([
            "status" => true,
            "data" => [
                "status" => $row['status'] ?? "pending",
                "mentorship_field" => $row['mentorship_field'] ?? "",
                "working_hours" => $row['working_hours'] ?? "",
                "mentorship_style" => $row['mentorship_style'] ?? ""
            ]
        ]);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
}

// Not applied - no record found in mentor_requests
echo json_encode([
    "status" => false,
    "message" => "No mentor application found"
]);

$conn->close();
?>
