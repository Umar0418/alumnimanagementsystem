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
    // Fixed: Column is 'usertype' not 'user_type'
    // Query only existing columns from users table
    $sql = "SELECT id, roll_no, name, email, phone, usertype, batch_number, degree FROM users WHERE usertype = 'student' ORDER BY name ASC";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            "id" => (int)$row['id'],
            "rollNo" => $row['roll_no'] ?? "",
            "name" => $row['name'] ?? "",
            "email" => $row['email'] ?? "",
            "phone" => $row['phone'] ?? "",
            "department" => $row['degree'] ?? "",  // Using degree as department
            "year" => $row['batch_number'] ?? ""   // Using batch_number as year
        ];
    }
    
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "students" => $students
    ], JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log("Get Students Error: " . $e->getMessage());
    echo json_encode([
        "status" => false,
        "message" => "Failed to retrieve students",
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_SLASHES);
}

$conn->close();
?>
