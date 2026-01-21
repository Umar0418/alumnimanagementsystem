<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Read POST data */
$roll_no     = trim($_POST['roll_no'] ?? '');
$department  = trim($_POST['department'] ?? '');
$batch_year  = trim($_POST['batch_year'] ?? '');
$company     = trim($_POST['company'] ?? '');
$location    = trim($_POST['location'] ?? '');
$mentorship  = isset($_POST['mentorship']) ? (int)$_POST['mentorship'] : 0;

/* Validate */
if ($roll_no === '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* Check if alumni already exists */
$check = $conn->prepare(
    "SELECT roll_no FROM alumni_directory WHERE roll_no = ?"
);

if (!$check) {
    echo json_encode([
        "status" => false,
        "error" => $conn->error
    ]);
    exit;
}

$check->bind_param("s", $roll_no);
$check->execute();
$check->store_result();

/* If not exists → INSERT, else UPDATE */
if ($check->num_rows === 0) {

    // INSERT new profile
    $sql = "INSERT INTO alumni_directory
            (roll_no, department, batch_year, company, location, mentorship)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status"=>false,"error"=>$conn->error]);
        exit;
    }

    $stmt->bind_param(
        "sssssi",
        $roll_no,
        $department,
        $batch_year,
        $company,
        $location,
        $mentorship
    );

} else {

    // UPDATE existing profile
    $sql = "UPDATE alumni_directory
            SET department = ?,
                batch_year = ?,
                company = ?,
                location = ?,
                mentorship = ?
            WHERE roll_no = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status"=>false,"error"=>$conn->error]);
        exit;
    }

    $stmt->bind_param(
        "ssssis",
        $department,
        $batch_year,
        $company,
        $location,
        $mentorship,
        $roll_no
    );
}

/* Execute */
if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Alumni profile updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "error" => $stmt->error
    ]);
}

$conn->close();
?>
