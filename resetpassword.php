<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include 'db.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email is required"]);
    exit;
}

if (empty($newPassword)) {
    echo json_encode(["status" => false, "message" => "New password is required"]);
    exit;
}

if (strlen($newPassword) < 6) {
    echo json_encode(["status" => false, "message" => "Password must be at least 6 characters"]);
    exit;
}

// Check if email exists in USERS table
$stmt = $conn->prepare("SELECT roll_no, name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $roll_no = $user['roll_no'];
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password in database
    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $updateStmt->bind_param("ss", $hashedPassword, $email);
    
    if ($updateStmt->execute()) {
        echo json_encode([
            "status" => true, 
            "message" => "Password reset successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false, 
            "message" => "Failed to update password. Please try again."
        ]);
    }
} else {
    echo json_encode([
        "status" => false, 
        "message" => "Email not found. Please check and try again."
    ]);
}

$conn->close();
?>
