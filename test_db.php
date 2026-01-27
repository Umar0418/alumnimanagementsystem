<?php
// Test database connection and user table structure
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require "db.php";

// Test connection
echo json_encode([
    "connection" => "success",
    "database" => "alumnidata"
]) . "\n\n";

// Check if users table exists and show its structure
$result = $conn->query("DESCRIBE users");

if ($result) {
    echo "Users table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo json_encode($row) . "\n";
    }
} else {
    echo json_encode([
        "error" => "Table 'users' not found or error: " . $conn->error
    ]);
}

// Try a simple test insert to see what happens
$test_name = "Test Admin";
$test_email = "test_" . time() . "@example.com";
$test_phone = "1234567890";
$test_password = "testpass123";
$test_usertype = "admin";

$sql = "INSERT INTO users (roll_no, name, email, phone, password, usertype) VALUES (NULL, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "\n\nPrepare failed: " . json_encode([
        "error" => $conn->error,
        "errno" => $conn->errno
    ]);
    exit;
}

$stmt->bind_param("sssss", $test_name, $test_email, $test_phone, $test_password, $test_usertype);

if ($stmt->execute()) {
    echo "\n\nTest insert SUCCESS! Inserted ID: " . $stmt->insert_id;
    // Delete the test record
    $conn->query("DELETE FROM users WHERE email = '$test_email'");
} else {
    echo "\n\nTest insert FAILED: " . json_encode([
        "error" => $stmt->error,
        "errno" => $stmt->errno
    ]);
}

$stmt->close();
$conn->close();
?>
