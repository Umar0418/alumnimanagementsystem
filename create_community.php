<?php
header("Content-Type: application/json");
require "db.php";

$name = $_POST['name'] ?? '';
$desc = $_POST['description'] ?? '';

if ($name=='') {
    echo json_encode(["status"=>false,"message"=>"community name required"]);
    exit;
}

$sql = "INSERT INTO communities (name, description) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss",$name,$desc);
$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Community created"]);
?>
