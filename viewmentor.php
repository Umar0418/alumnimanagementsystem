<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT 
            mr.roll_no,
            u.name,
            u.email,
            u.phone,
            mr.mentorship_field,
            mr.working_hours,
            mr.mentorship_style,
            mr.status
        FROM mentor_requests mr
        JOIN users u ON mr.roll_no = u.roll_no";

$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status"=>true,"requests"=>$data]);
?>
