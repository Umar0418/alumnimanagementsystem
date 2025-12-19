<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Read input safely */
$data = $_POST;

if (empty($data)) {
    $raw = file_get_contents("php://input");
    $json = json_decode($raw, true);
    if (is_array($json)) {
        $data = $json;
    }
}

/* If still empty */
if (empty($data)) {
    echo json_encode([
        "status" => false,
        "message" => "No input received",
        "debug" => "Send student_id or alumni_id"
    ]);
    exit;
}

/* Accept either student_id or alumni_id */
$user_id = $data['id'] ?? '';

if ($user_id == '') {
    echo json_encode([
        "status" => false,
        "message" => "User ID is required"
    ]);
    exit;
}

/* Check user exists */
$sql = "SELECT id, name, email, phone, usertype 
        FROM users 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => true,
        "exists" => true,
        "user" => $result->fetch_assoc()
    ]);
} else {
    echo json_encode([
        "status" => true,
        "exists" => false,
        "message" => "User not registered"
    ]);
}
?>
