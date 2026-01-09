<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$roll_no = $_POST['roll_no'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$department = $_POST['department'] ?? '';
$address = $_POST['address'] ?? '';
$cgpa = $_POST['cgpa'] ?? '';
$interests = $_POST['interests'] ?? '';

if ($roll_no == '') {
    echo '{"status":false,"message":"Roll number is required"}';
    exit;
}

// Update user profile
$sql = "UPDATE users SET 
        name = '" . $conn->real_escape_string($name) . "', 
        phone = '" . $conn->real_escape_string($phone) . "', 
        department = '" . $conn->real_escape_string($department) . "', 
        address = '" . $conn->real_escape_string($address) . "', 
        cgpa = '" . $conn->real_escape_string($cgpa) . "', 
        interests = '" . $conn->real_escape_string($interests) . "'
        WHERE roll_no = '" . $conn->real_escape_string($roll_no) . "'";

if ($conn->query($sql)) {
    echo '{"status":true,"message":"Profile updated successfully!"}';
} else {
    echo '{"status":false,"message":"Failed to update profile"}';
}

$conn->close();
?>
