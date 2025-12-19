<?php
header("Content-Type: application/json");
require "db.php";

$roll_no = $_POST['roll_no'] ?? '';
$community_id = $_POST['community_id'] ?? '';

if ($roll_no=='' || $community_id=='') {
    echo json_encode(["status"=>false,"message"=>"roll_no and community_id required"]);
    exit;
}

/* check alumni */
$check = $conn->prepare(
    "SELECT roll_no FROM users WHERE roll_no=? AND usertype='alumni'"
);
$check->bind_param("s",$roll_no);
$check->execute();
$check->store_result();

if ($check->num_rows==0) {
    echo json_encode(["status"=>false,"message"=>"Alumni not found"]);
    exit;
}

/* prevent duplicate join */
$dup = $conn->prepare(
    "SELECT id FROM community_members WHERE roll_no=? AND community_id=?"
);
$dup->bind_param("si",$roll_no,$community_id);
$dup->execute();
$dup->store_result();

if ($dup->num_rows>0) {
    echo json_encode(["status"=>false,"message"=>"Already joined"]);
    exit;
}

/* join */
$sql = "INSERT INTO community_members (community_id, roll_no)
        VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is",$community_id,$roll_no);
$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Joined community"]);
?>
