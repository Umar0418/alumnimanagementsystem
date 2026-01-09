<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

// First try with JOIN to get user details
$sql = "SELECT 
            mr.id,
            mr.roll_no,
            COALESCE(u.name, mr.roll_no) as name,
            COALESCE(u.email, '') as email,
            COALESCE(u.phone, '') as phone,
            mr.mentorship_field,
            mr.working_hours,
            mr.mentorship_style,
            mr.status
        FROM mentor_requests mr
        LEFT JOIN users u ON mr.roll_no = u.roll_no
        ORDER BY mr.id DESC";

$res = $conn->query($sql);
$data = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            "id" => $row['id'],
            "roll_no" => $row['roll_no'],
            "name" => $row['name'],
            "email" => $row['email'],
            "phone" => $row['phone'],
            "mentorship_field" => $row['mentorship_field'] ?? "",
            "working_hours" => $row['working_hours'] ?? "",
            "mentorship_style" => $row['mentorship_style'] ?? "",
            "status" => $row['status'] ?? "pending"
        ];
    }
}

echo json_encode([
    "status" => true,
    "requests" => $data,
    "count" => count($data)
]);

$conn->close();
?>
