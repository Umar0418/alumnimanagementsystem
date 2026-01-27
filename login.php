<?php
// Start output buffering to prevent any output before JSON response
ob_start();

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable error display temporarily to debug
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

try {
    require "db.php";
    
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($email === "" || $password === "") {
        if (ob_get_level()) ob_clean();
        echo json_encode(["status" => false, "message" => "Email and password are required"]);
        exit;
    }
    
    // Select all needed fields including student-specific fields
    // Only select columns that actually exist in the users table
    $sql = "SELECT id, roll_no, name, email, phone, usertype, password
            FROM users
            WHERE email = ?
            LIMIT 1";
    
    if (!$stmt = $conn->prepare($sql)) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!$stmt->bind_param("s", $email)) {
        throw new Exception("Bind failed: " . $stmt->error);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if ($user['password'] === $password) {
            $stmt->close();
            
            if (ob_get_level()) ob_clean();
            echo json_encode([
                "status" => true,
                "message" => "Login successful",
                "user" => [
                    "id" => (string)$user['id'],
                    "roll_no" => $user['roll_no'] ?? "",
                    "name" => $user['name'] ?? "",
                    "email" => $user['email'] ?? "",
                    "phone" => $user['phone'] ?? "",
                    "usertype" => $user['usertype'] ?? "",
                    "department" => null,  // Column doesn't exist in DB
                    "year" => null,        // Column doesn't exist in DB
                    "address" => null,     // Column doesn't exist in DB
                    "cgpa" => null,        // Column doesn't exist in DB
                    "interests" => null    // Column doesn't exist in DB
                ]
            ]);
        } else {
            $stmt->close();
            if (ob_get_level()) ob_clean();
            echo json_encode(["status" => false, "message" => "Incorrect password"]);
        }
    } else {
        $stmt->close();
        if (ob_get_level()) ob_clean();
        echo json_encode(["status" => false, "message" => "User not found"]);
    }
    
    ob_end_flush();
    $conn->close();
    
} catch (Exception $e) {
    if (ob_get_level()) ob_clean();
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        "status" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>