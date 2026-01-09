<?php
header("Content-Type: application/json");
require "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    $data = $_POST;
}

/* Logged-in alumni roll_no */
$roll_no = trim($data['roll_no'] ?? '');

if ($roll_no === '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* Fields to update (optional but allowed) */
$name        = trim($data['name'] ?? '');
$department  = trim($data['department'] ?? '');
$batch_year  = trim($data['batch_year'] ?? '');
$company     = trim($data['company'] ?? '');
$location    = trim($data['location'] ?? '');
$mentorship  = trim($data['mentorship'] ?? '');

/* Ensure alumni profile exists */
$check = $conn->prepare(
    "SELECT id FROM alumni_directory WHERE roll_no = ?"
);
$check->bind_param("s", $roll_no);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    echo json_encode([
        "status" => false,
        "message" => "Alumni profile does not exist"
    ]);
    exit;
}

/* Update ONLY this alumni */
$sql = "UPDATE alumni_directory
        SET name = ?, department = ?, batch_year = ?,
            company = ?, location = ?, mentorship = ?
        WHERE roll_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $name,
    $department,
    $batch_year,
    $company,
    $location,
    $mentorship,
    $roll_no
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Alumni profile updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to update alumni profile"
    ]);
}
