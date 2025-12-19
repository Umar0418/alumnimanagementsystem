<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Get form-data */
$roll_no = $_POST['roll_no'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$graduation_year = $_POST['graduation_year'] ?? '';
$degree = $_POST['degree'] ?? '';
$department = $_POST['department'] ?? '';

$password = "123";
$usertype = "alumni";

/* Validation */
if (
    $roll_no == "" || $name == "" || $email == "" || $phone == "" ||
    $graduation_year == "" || $degree == "" || $department == ""
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

/* Start transaction */
$conn->begin_transaction();

try {

    /* Insert into USERS table */
    $sql1 = "INSERT INTO users
        (roll_no, name, email, phone, password, usertype)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt1 = $conn->prepare($sql1);
    if (!$stmt1) {
        throw new Exception("Users prepare failed: " . $conn->error);
    }

    $stmt1->bind_param(
        "ssssss",
        $roll_no,
        $name,
        $email,
        $phone,
        $password,
        $usertype
    );

    if (!$stmt1->execute()) {
        throw new Exception("Users insert failed: " . $stmt1->error);
    }

    /* Insert into ALUMNI table */
    $sql2 = "INSERT INTO alumni
        (roll_no, graduation_year, degree, department)
        VALUES (?, ?, ?, ?)";

    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        throw new Exception("Alumni prepare failed: " . $conn->error);
    }

    $stmt2->bind_param(
        "ssss",
        $roll_no,
        $graduation_year,
        $degree,
        $department
    );

    if (!$stmt2->execute()) {
        throw new Exception("Alumni insert failed: " . $stmt2->error);
    }

    /* Commit both inserts */
    $conn->commit();

    echo json_encode([
        "status" => true,
        "message" => "Alumni details stored in BOTH users and alumni tables"
    ]);

} catch (Exception $e) {

    /* Rollback if ANY insert fails */
    $conn->rollback();

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
