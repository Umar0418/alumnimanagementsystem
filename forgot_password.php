<?php
header("Content-Type: application/json");
<<<<<<< HEAD

// --- IMPORTANT: CONFIGURE YOUR EMAIL SETTINGS HERE ---
$your_email_address = 'your_email@gmail.com'; // The email you're sending from (e.g., your.app@gmail.com)
$your_email_password = 'your_gmail_app_password'; // IMPORTANT: Use a Google App Password, not your real password!
$your_name = 'Alumni Management App'; // The "From" name that appears in the email
// ----------------------------------------------------

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require the autoloader
require 'vendor/autoload.php';

require "db.php";

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email address is required"]);
    exit;
}

// Check if the user exists
$check_sql = "SELECT roll_no, name FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($check_sql);
=======
header("Access-Control-Allow-Origin: *");

include 'db.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    echo json_encode(["status" => false, "message" => "Email is required"]);
    exit;
}

// Check email in USERS table
$stmt = $conn->prepare("SELECT roll_no, name FROM users WHERE email = ?");
>>>>>>> f1faf11f584ac842cd937ffc9bf01ed330e2c2fd
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

<<<<<<< HEAD
if ($user = $result->fetch_assoc()) {
    $user_name = $user['name'];
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->isSMTP();                                     // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                            // Enable SMTP authentication
        $mail->Username   = $your_email_address;             // SMTP username
        $mail->Password   = $your_email_password;            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Enable implicit TLS encryption
        $mail->Port       = 465;                             // TCP port to connect to

        // --- Recipients ---
        $mail->setFrom($your_email_address, $your_name);
        $mail->addAddress($email, $user_name);               // Add a recipient

        // --- Content ---
        $mail->isHTML(true);                                 // Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
            <h2>Password Reset</h2>
            <p>Hi $user_name,</p>
            <p>We received a request to reset your password. You can reset your password by clicking the link below:</p>
            <p><a href='http://your-app-domain.com/reset_password.php?token=some_unique_token'>Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email.</p>
        ";
        $mail->AltBody = 'To reset your password, please visit: http://your-app-domain.com/reset_password.php?token=some_unique_token';

        // Actually send the email
        $mail->send();

        // Send success response to the app
        echo json_encode(['status' => true, 'message' => 'Password reset link sent successfully!']);

    } catch (Exception $e) {
        // If PHPMailer fails, send a detailed error
        echo json_encode(['status' => false, 'message' => "Failed to send email. Mailer Error: {$mail->ErrorInfo}"]);
    }

} else {
    // User not found, but we send a generic success message for security to not reveal who is registered.
    echo json_encode(["status" => true, "message" => "If an account with that email exists, a reset link has been sent."]);
}

=======
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
>>>>>>> f1faf11f584ac842cd937ffc9bf01ed330e2c2fd
?>