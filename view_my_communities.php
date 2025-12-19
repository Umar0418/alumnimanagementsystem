<?php
header("Content-Type: application/json");
require "db.php";

$roll_no = $_POST['roll_no'] ?? '';

if ($roll_no=='') {
    echo json_encode(["status"=>false,"message"=>"roll_no required"]);
    exit;
}

$sql = "SELECT 
            c.id,
            c.name,
            c.description
        FROM community_members cm
        JOIN communities c ON cm.community_id = c.id
        WHERE cm.roll_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$roll_no);
$stmt->execute();

$res = $stmt->get_result();
$data=[];

while($row=$res->fetch_assoc()){
    $data[]=$row;
}

echo json_encode(["status"=>true,"communities"=>$data]);
?>
