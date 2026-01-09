<?php
header("Content-Type: application/json");
require "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    $data = $_POST;
}

/* Logged-in alumni roll_no (from login) */
$roll_no = trim($data['roll_no'] ?? '');

if ($roll_no === '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* 1️⃣ Check if profile already exists */
$check = $conn->prepare(
    "SELECT roll_no, name, department, batch_year, company, location, mentorship
     FROM alumni_directory
     WHERE roll_no = ?"
);
$check->bind_param("s", $roll_no);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    /* Profile exists → return it */
    $profile = $res->fetch_assoc();
    echo json_encode([
        "status" => true,
        "profile" => $profile
    ]);
    exit;
}

/* 2️⃣ Profile does NOT exist → fetch basic data from users table */
$userQ = $conn->prepare(
    "SELECT roll_no, name
     FROM users
     WHERE roll_no = ? AND usertype = 'alumni'"
);
$userQ->bind_param("s", $roll_no);
$userQ->execute();
$userRes = $userQ->get_result();

if ($userRes->num_rows === 0) {
    echo json_encode([
        "status" => false,
        "message" => "Logged-in alumni not found in users table"
    ]);
    exit;
}

$user = $userRes->fetch_assoc();

/* 3️⃣ Create alumni profile automatically */
$insert = $conn->prepare(
    "INSERT INTO alumni_directory
     (roll_no, name, department, batch_year, company, location, mentorship)
     VALUES (?, ?, '', '', '', '', 'no')"
);
$insert->bind_param("ss", $user['roll_no'], $user['name']);
$insert->execute();

/* 4️⃣ Return newly created profile */
echo json_encode([
    "status" => true,
    "profile" => [
        "roll_no" => $user['roll_no'],
        "name" => $user['name'],
        "department" => "",
        "batch_year" => "",
        "company" => "",
        "location" => "",
        "mentorship" => "no"
    ],
    "message" => "Alumni profile created automatically"
]);
