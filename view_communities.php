<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT * FROM communities";
$res = $conn->query($sql);

$data=[];
while($row=$res->fetch_assoc()){
    $data[]=$row;
}

echo json_encode(["status"=>true,"communities"=>$data]);
?>
