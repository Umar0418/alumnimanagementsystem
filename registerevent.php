<?php
header("Content-Type: application/json");
require "db.php";

$roll_no  = $_POST['roll_no'] ?? '';
$event_id = $_POST['event_id'] ?? '';

if ($roll_no=='' || $event_id=='') {
    echo json_encode(["status"=>false,"message"=>"roll_no and event_id required"]);
    exit;
}

/* check user */
$check = $conn->prepare("SELECT roll_no FROM users WHERE roll_no=?");
$check->bind_param("s",$roll_no);
$check->execute();
$check->store_result();

if ($check->num_rows==0) {
    echo json_encode(["status"=>false,"message"=>"User not found"]);
    exit;
}

/* register */
$sql = "INSERT INTO event_registrations (event_id, roll_no)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is",$event_id,$roll_no);
$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Event registered"]);
?>
