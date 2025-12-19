<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

/* Get form-data */
$roll_no          = $_POST['roll_no'] ?? '';
$name             = $_POST['name'] ?? '';
$email            = $_POST['email'] ?? '';
$phone            = $_POST['phone'] ?? '';
$graduation_year  = $_POST['graduation_year'] ?? '';
$degree           = $_POST['degree'] ?? '';
$department       = $_POST['department'] ?? '';

$password = "123";
$usertype = "student";

/* Validation */
if (
    $roll_no=="" || $name=="" || $email=="" || $phone=="" ||
    $graduation_year=="" || $degree=="" || $department==""
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

/* Check duplicate roll number */
$check = $conn->prepare("SELECT id FROM users WHERE roll_no=?");
$check->bind_param("s", $roll_no);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Roll number already registered"
    ]);
    exit;
}

/* Start transaction */
$conn->begin_transaction();

try {

    /* Insert into USERS */
    $user_sql = "INSERT INTO users
        (roll_no, name, email, phone, password, usertype)
        VALUES (?, ?, ?, ?, ?, ?)";

    $u = $conn->prepare($user_sql);
    $u->bind_param("ssssss", $roll_no, $name, $email, $phone, $password, $usertype);
    $u->execute();

    /* Insert into STUDENTS */
    $student_sql = "INSERT INTO students
        (roll_no, name, graduation_year, degree, department)
        VALUES (?, ?, ?, ?, ?)";

    $s = $conn->prepare($student_sql);
    $s->bind_param("sssss", $roll_no, $name, $graduation_year, $degree, $department);
    $s->execute();

    $conn->commit();

    echo json_encode([
        "status" => true,
        "message" => "Student registered successfully in users & students tables",
        "default_password" => "123"
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
