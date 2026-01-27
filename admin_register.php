<?php
// Start output buffering to prevent any accidental output
ob_start();

// Set headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Disable error display but log errors
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    require "db.php";

    /* Get form-data */
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    /* Debug: Log received data */
    error_log("Admin Registration - Received data: name=$name, email=$email, phone=$phone");

    /* Validation */
    $missing_fields = [];
    if ($name == "") $missing_fields[] = "name";
    if ($email == "") $missing_fields[] = "email";
    if ($phone == "") $missing_fields[] = "phone";
    if ($password == "") $missing_fields[] = "password";

    if (!empty($missing_fields)) {
        ob_clean(); // Clear any buffered output
        echo json_encode([
            "status" => false,
            "message" => "All fields are required. Missing: " . implode(", ", $missing_fields)
        ], JSON_UNESCAPED_SLASHES);
        exit;
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ob_clean();
        echo json_encode([
            "status" => false,
            "message" => "Please enter a valid email address"
        ], JSON_UNESCAPED_SLASHES);
        exit;
    }

    /* Check if email already exists in admins table */
    $checkSql = "SELECT id FROM admins WHERE email = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    
    if (!$checkStmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }
    
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $checkStmt->close();
        ob_clean();
        echo json_encode([
            "status" => false,
            "message" => "An account with this email already exists"
        ], JSON_UNESCAPED_SLASHES);
        exit;
    }
    $checkStmt->close();

    /* Insert into ADMINS table */
    $sql = "INSERT INTO admins (name, email, phone, password) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $password);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        ob_clean();
        echo json_encode([
            "status" => true,
            "message" => "Admin account created successfully! Please login."
        ], JSON_UNESCAPED_SLASHES);
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        ob_clean();
        echo json_encode([
            "status" => false,
            "message" => "Registration failed: " . $error
        ], JSON_UNESCAPED_SLASHES);
    }
} catch (Exception $e) {
    // Handle any exceptions
    error_log("Admin Registration Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    ob_clean();
    echo json_encode([
        "status" => false,
        "message" => "Error: " . $e->getMessage(),
        "debug" => "Check if database connection is working and users table exists with correct columns"
    ], JSON_UNESCAPED_SLASHES);
}

// End output buffering and flush
ob_end_flush();
?>
