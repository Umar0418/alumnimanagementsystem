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
    // Get email from request
    $email = $_POST['email'] ?? '';
    
    error_log("=== Get Job Referrals Request ===");
    error_log("Email: $email");
    
    if (empty($email)) {
        throw new Exception("Email is required");
    }
    
    // Get roll_no from users table using email
    $user_query = "SELECT roll_no FROM users WHERE email = ? AND usertype = 'alumni'";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("s", $email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows == 0) {
        throw new Exception("User not found");
    }
    
    $user_row = $user_result->fetch_assoc();
    $roll_no = $user_row['roll_no'];
    $user_stmt->close();
    
    error_log("Roll No found: $roll_no");
    
    // Query to get jobs posted by this alumni
    $query = "SELECT * FROM jobs WHERE posted_by = ? ORDER BY created_at DESC";
    
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
    
    $jobs = array();
    
    while ($row = $result->fetch_assoc()) {
        error_log("Found job: " . $row['title']);
        $jobs[] = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "company" => $row['company'],
            "description" => $row['description'] ?? "",
            "location" => $row['location'] ?? "Not specified",
            "job_type" => $row['job_type'] ?? "Full-time",
            "salary" => $row['salary'] ?? "Not disclosed",
            "last_date" => $row['last_date'] ?? "",
            "posted_date" => date('M d, Y', strtotime($row['created_at']))
        );
    }
    
    $stmt->close();
    
    error_log("Total jobs returned: " . count($jobs));
    
    ob_end_clean();
    $response = array(
        "status" => true,
        "message" => count($jobs) > 0 ? "Job referrals fetched successfully" : "No job referrals found",
        "jobs" => $jobs
    );
    
    error_log("Response: " . json_encode($response));
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    ob_end_clean();
    error_log("Get Job Referrals Error: " . $e->getMessage());
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage(),
        "jobs" => []
    ], JSON_UNESCAPED_SLASHES);
}

$conn->close();
?>
