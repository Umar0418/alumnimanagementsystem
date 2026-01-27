<?php
// Suppress errors and enable output buffering for clean JSON
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";
ob_clean();

try {
    // Get data from request
    $roll_no = $_POST['roll_no'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $graduation_year = $_POST['graduation_year'] ?? '';
    $degree = $_POST['degree'] ?? '';
    $department = $_POST['department'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Log received data for debugging
    error_log("Alumni Registration - Roll: $roll_no, Email: $email");
    
    // Validate required fields
    if (empty($roll_no) || empty($name) || empty($email) || empty($password)) {
        throw new Exception("All required fields must be filled");
    }
    
    // Check if email already exists in users table
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        throw new Exception("Email already exists");
    }
    $stmt_check->close();
    
    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (roll_no, name, email, phone, password, usertype, batch_number, degree) VALUES (?, ?, ?, ?, ?, 'alumni', ?, ?)");
    $stmt->bind_param("sssssss", $roll_no, $name, $email, $phone, $password, $graduation_year, $degree);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to create user account: " . $stmt->error);
    }
    
    $user_id = $conn->insert_id;
    $stmt->close();
    
    // Insert into alumni_directory table
    $stmt_alumni = $conn->prepare("INSERT INTO alumni_directory (user_id, name, email, phone, roll_no, graduation_year, degree, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_alumni->bind_param("isssssss", $user_id, $name, $email, $phone, $roll_no, $graduation_year, $degree, $department);
    
    if (!$stmt_alumni->execute()) {
        // Rollback - delete the user entry if alumni_directory insert fails
        $conn->query("DELETE FROM users WHERE id = $user_id");
        throw new Exception("Failed to create alumni profile: " . $stmt_alumni->error);
    }
    
    $stmt_alumni->close();
    
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "message" => "Alumni registration successful",
        "userId" => $user_id
    ], JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log("Alumni Registration Error: " . $e->getMessage());
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_SLASHES);
}

$conn->close();
?>
