<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Read POST data */
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($name === "" || $description === "") {
    echo json_encode([
        "status" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

/* Duplicate check */
$check = $conn->prepare(
    "SELECT id FROM communities WHERE name = ?"
);

if (!$check) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$check->bind_param("s", $name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "A community with this name already exists"
    ]);
    exit;
}

/* Insert */
$sql = "INSERT INTO communities (name, description)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$stmt->bind_param("ss", $name, $description);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Community created successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "error" => $stmt->error
    ]);
}
?>
