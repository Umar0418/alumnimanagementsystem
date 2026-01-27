<?php
// Suppress errors and enable output buffering for clean JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ob_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";
ob_clean();

try {
    // Get roll number from request
    $roll_no = $_POST['roll_no'] ?? '';
    
    error_log("=== Get Applied Jobs Request ===");
    error_log("Roll No: $roll_no");
    error_log("POST data: " . print_r($_POST, true));
    
    if (empty($roll_no)) {
        throw new Exception("Roll number is required");
    }
    
    // First check if job_applications table exists and has data
    $check_query = "SELECT COUNT(*) as total FROM job_applications WHERE roll_no = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $roll_no);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();
    $total_apps = $check_row['total'];
    $check_stmt->close();
    
    error_log("Total applications found for roll_no '$roll_no': $total_apps");
    
    // Query to get applied jobs for this user
    $query = "SELECT 
                ja.id as application_id,
                ja.job_id,
                ja.status,
                ja.created_at as applied_date,
                j.title as job_title,
                j.company,
                j.location,
                j.salary
              FROM job_applications ja
              INNER JOIN jobs j ON ja.job_id = j.id
              WHERE ja.roll_no = ?
              ORDER BY ja.created_at DESC";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        throw new Exception("Database query preparation failed");
    }
    
    $stmt->bind_param("s", $roll_no);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        throw new Exception("Database query execution failed");
    }
    
    $result = $stmt->get_result();
    
    $applications = array();
    
    while ($row = $result->fetch_assoc()) {
        error_log("Found application: " . print_r($row, true));
        $applications[] = array(
            "application_id" => $row['application_id'],
            "job_id" => $row['job_id'],
            "job_title" => $row['job_title'],
            "company" => $row['company'],
            "location" => $row['location'] ?? "Not specified",
            "salary" => $row['salary'] ?? "Not disclosed",
            "applied_date" => date('M d, Y', strtotime($row['applied_date'])),
            "status" => $row['status'] ?? "pending"
        );
    }
    
    $stmt->close();
    
    error_log("Total applications returned: " . count($applications));
    
    ob_end_clean();
    $response = array(
        "status" => true,
        "message" => count($applications) > 0 ? "Applied jobs fetched successfully" : "No applications found",
        "applications" => $applications,
        "count" => count($applications)
    );
    
    error_log("Response: " . json_encode($response));
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log("Get Applied Jobs Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage(),
        "applications" => [],
        "count" => 0
    ], JSON_UNESCAPED_SLASHES);
}

$conn->close();
?>
