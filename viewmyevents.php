<?php
header("Content-Type: application/json");
require "db.php";

$roll_no = $_POST['roll_no'] ?? '';

if ($roll_no=='') {
    echo json_encode(["status"=>false,"message"=>"roll_no required"]);
    exit;
}

$sql = "SELECT 
            e.title,
            e.description,
            e.event_date
        FROM event_registrations er
        JOIN events e ON er.event_id = e.id
        WHERE er.roll_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$roll_no);
$stmt->execute();

$res = $stmt->get_result();
$data=[];

while($row=$res->fetch_assoc()){
    $data[]=$row;
}

echo json_encode(["status"=>true,"events"=>$data]);
?>
