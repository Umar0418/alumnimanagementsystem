<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

$mentors = [];

// Get approved mentors from mentor_requests table
$sql = "SELECT 
            mr.roll_no,
            mr.mentorship_field,
            mr.working_hours,
            mr.mentorship_style,
            mr.status,
            mr.created_at,
            u.name,
            u.email,
            u.phone
        FROM mentor_requests mr
        INNER JOIN users u ON mr.roll_no = u.roll_no
        WHERE mr.status = 'approved'
        ORDER BY mr.created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mentors[] = [
            "roll_no" => $row['roll_no'] ?? "",
            "name" => $row['name'] ?? "",
            "email" => $row['email'] ?? "",
            "phone" => $row['phone'] ?? "",
            "mentorship_field" => $row['mentorship_field'] ?? "",
            "working_hours" => $row['working_hours'] ?? "",
            "mentorship_style" => $row['mentorship_style'] ?? "",
            "status" => $row['status'] ?? "",
            "created_at" => $row['created_at'] ?? ""
        ];
    }
}

echo json_encode([
    "status" => true,
    "count" => count($mentors),
    "requests" => $mentors
]);

$conn->close();
?>
