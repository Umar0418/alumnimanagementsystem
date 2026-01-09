<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require 'db.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$userType = isset($_POST['user_type']) ? trim($_POST['user_type']) : '';

if (empty($email)) {
    echo '{"status":false,"message":"Email is required"}';
    exit;
}

if (empty($newPassword)) {
    echo '{"status":false,"message":"New password is required"}';
    exit;
}

if (strlen($newPassword) < 6) {
    echo '{"status":false,"message":"Password must be at least 6 characters"}';
    exit;
}

// Check if email exists and matches user type
$sql = "SELECT roll_no, usertype FROM users WHERE email = '" . $conn->real_escape_string($email) . "'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $actualUserType = strtolower($user['usertype']);
    $requestedType = strtolower($userType);
    
    // If user type is specified, verify it matches
    if (!empty($userType) && $actualUserType !== $requestedType) {
        if ($requestedType == 'student') {
            echo '{"status":false,"message":"This email is not registered as a student account"}';
        } else if ($requestedType == 'alumni') {
            echo '{"status":false,"message":"This email is not registered as an alumni account"}';
        } else {
            echo '{"status":false,"message":"Email does not match the account type"}';
        }
        exit;
    }
    
    // Update password
    $updateSql = "UPDATE users SET password = '" . $conn->real_escape_string($newPassword) . "' WHERE email = '" . $conn->real_escape_string($email) . "'";
    
    if ($conn->query($updateSql)) {
        echo '{"status":true,"message":"Password reset successfully!"}';
    } else {
        echo '{"status":false,"message":"Failed to update password"}';
    }
} else {
    echo '{"status":false,"message":"Email not found. Please check and try again."}';
}

$conn->close();
?>
