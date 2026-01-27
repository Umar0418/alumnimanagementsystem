<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db.php";

// Log incoming request for debugging
error_log("Student Registration Request: " . json_encode($_POST));

/* Get form-data */
$roll_no     = trim($_POST['roll_no'] ?? '');
$name        = trim($_POST['name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$year        = trim($_POST['year'] ?? '');
$department  = trim($_POST['department'] ?? '');
$password    = trim($_POST['password'] ?? '');

$usertype = "student";

/* Validation */
if (
    $roll_no=="" || $name=="" || $email=="" || 
    $year=="" || $department=="" || $password==""
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

/* Check duplicate roll number */
$check = $conn->prepare("SELECT id FROM users WHERE roll_no=?");
if (!$check) {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

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
$check->close();

/* Check duplicate email */
$check_email = $conn->prepare("SELECT id FROM users WHERE email=?");
if (!$check_email) {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Email already registered"
    ]);
    exit;
}
$check_email->close();

/* Start transaction */
$conn->begin_transaction();

try {

    /* Insert into USERS table */
    $user_sql = "INSERT INTO users
        (roll_no, name, email, password, usertype)
        VALUES (?, ?, ?, ?, ?)";

    $u = $conn->prepare($user_sql);
    if (!$u) {
        throw new Exception("Failed to prepare users statement: " . $conn->error);
    }
    
    $u->bind_param("sssss", $roll_no, $name, $email, $password, $usertype);
    
    if (!$u->execute()) {
        throw new Exception("Failed to insert into users table: " . $u->error);
    }
    $u->close();

    /* Insert into STUDENTS table */
    // The students table structure from the SQL dump shows: id, name, roll_no, email, phone, password, graduation_year, degree, department
    // We'll insert the essential fields and leave optional ones empty
    
    $student_sql = "INSERT INTO students
        (roll_no, name, email, department, graduation_year)
        VALUES (?, ?, ?, ?, ?)";

    $s = $conn->prepare($student_sql);
    if (!$s) {
        throw new Exception("Failed to prepare students statement: " . $conn->error);
    }
    
    $s->bind_param("sssss", $roll_no, $name, $email, $department, $year);
    
    if (!$s->execute()) {
        throw new Exception("Failed to insert into students table: " . $s->error);
    }
    $s->close();

    // Commit the transaction
    $conn->commit();

    // Log success
    error_log("Student Registration Success: Roll No: $roll_no, Name: $name, Email: $email");

    echo json_encode([
        "status" => true,
        "message" => "Registration successful"
    ]);

} catch (Exception $e) {

    // Rollback on error
    $conn->rollback();
    
    // Log the error
    error_log("Student Registration Error: " . $e->getMessage());

    echo json_encode([
        "status" => false,
        "message" => "Registration failed: " . $e->getMessage()
    ]);
}

$conn->close();
?>
