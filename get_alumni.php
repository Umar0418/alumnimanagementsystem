<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors to ensure clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

// First, let's get all alumni from users table
$sql = "SELECT roll_no, name, email, phone FROM users WHERE usertype = 'alumni' ORDER BY name ASC";
$result = $conn->query($sql);

$alumni = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roll_no = $row['roll_no'];
        
        // Try to get additional details from alumni_directory
        $details_sql = "SELECT department, batch_year, company, location FROM alumni_directory WHERE roll_no = ?";
        $stmt = $conn->prepare($details_sql);
        
        $department = "";
        $batch_year = "";
        $company = "";
        $designation = "";
        
        if ($stmt) {
            $stmt->bind_param("s", $roll_no);
            $stmt->execute();
            $details_result = $stmt->get_result();
            if ($details_row = $details_result->fetch_assoc()) {
                $department = $details_row['department'] ?? "";
                $batch_year = $details_row['batch_year'] ?? "";
                $company = $details_row['company'] ?? "";
                $designation = $details_row['location'] ?? "";  // location used as designation
            }
            $stmt->close();
        }
        
        $alumni[] = [
            "id" => $row['roll_no'] ?? "",
            "name" => $row['name'] ?? "",
            "email" => $row['email'] ?? "",
            "phone" => $row['phone'] ?? "",
            "company" => $company,
            "designation" => $designation,
            "batch_year" => $batch_year,
            "department" => $department,
            "linkedin" => "",
            "skills" => ""
        ];
    }
}

echo json_encode([
    "status" => true,
    "count" => count($alumni),
    "alumni" => $alumni
]);

$conn->close();
?>
