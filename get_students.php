<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

$students = [];

// Simple query - just get students from users table
$sql = "SELECT roll_no, name, email, phone, department, year FROM users WHERE usertype = 'student' ORDER BY name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            "id" => $row['roll_no'] ?? "",
            "roll_no" => $row['roll_no'] ?? "",
            "name" => $row['name'] ?? "",
            "email" => $row['email'] ?? "",
            "phone" => $row['phone'] ?? "",
            "department" => $row['department'] ?? "",
            "year" => $row['year'] ?? "",
            "address" => "",
            "cgpa" => "",
            "interests" => ""
        ];
    }
}

// Output result
echo json_encode([
    "status" => true,
    "count" => count($students),
    "students" => $students
]);

$conn->close();
?>
