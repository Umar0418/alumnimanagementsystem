<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

$students = [];

<<<<<<< HEAD
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

=======
try {
    // Simple query - get basic columns that definitely exist
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
    
    echo json_encode([
        "status" => true,
        "count" => count($students),
        "students" => $students
    ]);
    
} catch (Exception $e) {
    // If there's an error with column names, try minimal columns
    $sql = "SELECT roll_no, name, email FROM users WHERE usertype = 'student' ORDER BY name ASC";
    $result = $conn->query($sql);
    
    $students = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = [
                "id" => $row['roll_no'] ?? "",
                "roll_no" => $row['roll_no'] ?? "",
                "name" => $row['name'] ?? "",
                "email" => $row['email'] ?? "",
                "phone" => "",
                "department" => "",
                "year" => "",
                "address" => "",
                "cgpa" => "",
                "interests" => ""
            ];
        }
    }
    
    echo json_encode([
        "status" => true,
        "count" => count($students),
        "students" => $students
    ]);
}

>>>>>>> f1faf11f584ac842cd937ffc9bf01ed330e2c2fd
$conn->close();
?>
