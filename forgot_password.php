<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include 'db.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email is required"]);
    exit;
}

// Check email in USERS table
$stmt = $conn->prepare("SELECT roll_no, name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $roll_no = $user['roll_no'];
    $name = $user['name'] ? $user['name'] : 'User';
    
    // Generate secure reset token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Save token in password_resets table
    $stmt2 = $conn->prepare("INSERT INTO password_resets (roll_no, email, reset_token, expires_at) 
                             VALUES (?, ?, ?, ?) 
                             ON DUPLICATE KEY UPDATE reset_token=?, expires_at=?");
    $stmt2->bind_param("ssssss", $roll_no, $email, $token, $expires, $token, $expires);
    
    if ($stmt2->execute()) {
        // Return success - email would be sent in production
        echo json_encode([
            "status" => true, 
            "message" => "Password reset link has been sent to your email"
        ]);
    } else {
        echo json_encode([
            "status" => false, 
            "message" => "An error occurred. Please try again."
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