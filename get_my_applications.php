<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('max_execution_time', 30);
ob_start();

require "db.php";
ob_clean();

try {
    $roll_no = $_POST['roll_no'] ?? '';
    
    error_log("=== Get My Applications Request ===");
    error_log("Roll No: $roll_no");
    
    if (empty($roll_no)) {
        throw new Exception("Roll number is required");
    }
    
    // Optimized query with INNER JOIN instead of LEFT JOIN for better performance
    $sql = "SELECT 
                ja.id as application_id,
                ja.job_id,
                ja.status as application_status,
                ja.created_at as applied_date,
                j.id,
                j.title,
                j.company,
                j.description,
                j.location,
                j.job_type,
                j.salary,
                j.last_date,
                j.created_at
            FROM job_applications ja
            INNER JOIN jobs j ON ja.job_id = j.id
            WHERE ja.roll_no = ?
            ORDER BY ja.created_at DESC
            LIMIT 100";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        throw new Exception("Database error");
    }
    
    $stmt->bind_param("s", $roll_no);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        throw new Exception("Failed to fetch applications");
    }
    
    $result = $stmt->get_result();
    $appliedJobs = [];
    
    while ($row = $result->fetch_assoc()) {
        error_log("Found application for job: " . $row['title']);
        $appliedJobs[] = [
            "application_id" => (int)$row['application_id'],
            "id" => (int)$row['id'],
            "title" => $row['title'],
            "company" => $row['company'],
            "description" => $row['description'] ?? "",
            "location" => $row['location'] ?? "Not specified",
            "job_type" => $row['job_type'] ?? "Full-time",
            "salary" => $row['salary'] ?? "Not disclosed",
            "last_date" => $row['last_date'] ?? "",
            "application_status" => $row['application_status'] ?? "pending",
            "applied_date" => date('Y-m-d H:i:s', strtotime($row['applied_date'])),
            "created_at" => date('Y-m-d H:i:s', strtotime($row['created_at']))
        ];
    }
    
    $stmt->close();
    
    error_log("Returning " . count($appliedJobs) . " applications");
    
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "count" => count($appliedJobs),
        "jobs" => $appliedJobs,
        "message" => count($appliedJobs) > 0 ? "Applications loaded successfully" : "No applications found"
    ], JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log("Get Applications Error: " . $e->getMessage());
    echo json_encode([
        "status" => false,
        "count" => 0,
        "jobs" => [],
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_SLASHES);
}

$conn->close();
?>
