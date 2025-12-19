<?php
header("Content-Type: application/json");
require "db.php";

$community_id = $_POST['community_id'] ?? '';

if ($community_id=='') {
    echo json_encode(["status"=>false,"message"=>"community_id required"]);
    exit;
}

$sql = "SELECT 
            u.roll_no,
            u.name,
            u.email,
            u.phone
        FROM community_members cm
        JOIN users u ON cm.roll_no = u.roll_no
        WHERE cm.community_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$community_id);
$stmt->execute();

$res = $stmt->get_result();
$data=[];

while($row=$res->fetch_assoc()){
    $data[]=$row;
}

echo json_encode(["status"=>true,"members"=>$data]);
?>
