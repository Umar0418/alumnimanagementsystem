<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$usertype = $_POST['usertype'] ?? '';

if ($usertype == "") {
    echo json_encode(["status" => false, "message" => "User type required"]);
    exit;
}

$sql = "SELECT * FROM announcements
        WHERE target = 'both' OR target = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usertype);
$stmt->execute();

$result = $stmt->get_result();

$announcements = [];
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

echo json_encode([
    "status" => true,
    "announcements" => $announcements
]);
?>
