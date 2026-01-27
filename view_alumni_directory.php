<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

$alumni = [];

// Try to get data from alumni_directory table
$sql = "SELECT 
            roll_no,
            name,
            department,
            batch_year,
            company,
            location,
            mentorship
        FROM alumni_directory
        ORDER BY name ASC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumni[] = [
            "roll_no" => $row['roll_no'] ?? "",
            "name" => $row['name'] ?? "",
            "department" => $row['department'] ?? "",
            "batch_year" => $row['batch_year'] ?? "",
            "company" => $row['company'] ?? "",
            "location" => $row['location'] ?? "",
            "mentorship" => (int)($row['mentorship'] ?? 0)
        ];
    }
}

// If alumni_directory is empty, try to get from users table
if (empty($alumni)) {
    $sql2 = "SELECT roll_no, name, email, phone FROM users WHERE usertype = 'alumni' ORDER BY name ASC";
    $result2 = $conn->query($sql2);
    
    if ($result2 && $result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $alumni[] = [
                "roll_no" => $row['roll_no'] ?? "",
                "name" => $row['name'] ?? "",
                "department" => "",
                "batch_year" => "",
                "company" => "",
                "location" => "",
                "mentorship" => 0
            ];
        }
    }
}

echo json_encode([
    "status" => true,
    "alumni" => $alumni
]);

$conn->close();
?>
