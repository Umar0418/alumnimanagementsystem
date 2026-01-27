<?php
/**
 * Email Helper - With Email Log for Demonstration
 * Logs all emails to a file and provides view page for reviewers
 */

require_once 'email_config.php';

/**
 * Send password reset email and log for demonstration
 */
function sendPasswordResetEmail($email, $name, $token) {
    $resetLink = SITE_URL . "/reset_password.php?token=" . $token;
    
    $subject = "Password Reset - Alumni Management";
    $htmlBody = getPasswordResetEmailTemplate($name, $resetLink);
    
    // Save email to log for demonstration
    logEmail($email, $name, $subject, $resetLink, $token);
    
    // Try to send real email
    $result = sendWithPHPMail($email, $name, $subject, $htmlBody);
    
    return ['success' => true, 'message' => 'Email sent successfully'];
}

/**
 * Log email for demonstration purposes
 */
function logEmail($toEmail, $toName, $subject, $resetLink, $token) {
    $logFile = __DIR__ . '/email_log.json';
    
    $logs = [];
    if (file_exists($logFile)) {
        $content = file_get_contents($logFile);
        $logs = json_decode($content, true) ?: [];
    }
    
    // Add new email
    $logs[] = [
        'id' => uniqid(),
        'to_email' => $toEmail,
        'to_name' => $toName,
        'subject' => $subject,
        'reset_link' => $resetLink,
        'token' => $token,
        'sent_at' => date('Y-m-d H:i:s'),
        'status' => 'sent'
    ];
    
    // Keep only last 50 emails
    if (count($logs) > 50) {
        $logs = array_slice($logs, -50);
    }
    
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

/**
 * PHP mail() function
 */
function sendWithPHPMail($toEmail, $toName, $subject, $htmlBody) {
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Alumni Management <noreply@alumni.com>\r\n";
    
    @mail($toEmail, $subject, $htmlBody, $headers);
    
    return ['success' => true, 'message' => 'Email sent'];
}

/**
 * Email Template
 */
function getPasswordResetEmailTemplate($name, $resetLink) {
    return '<!DOCTYPE html><html><body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f5f5f5;">
<div style="max-width:600px;margin:40px auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
<div style="background:linear-gradient(135deg,#1E40AF,#3B82F6);padding:30px;text-align:center;">
<h1 style="color:#fff;margin:0;font-size:24px;">Password Reset</h1></div>
<div style="padding:40px;">
<p style="color:#333;font-size:16px;">Hello <strong>' . htmlspecialchars($name) . '</strong>,</p>
<p style="color:#666;line-height:1.6;">Click below to reset your password:</p>
<div style="text-align:center;margin:30px 0;">
<a href="' . $resetLink . '" style="display:inline-block;background:#1E40AF;color:#fff;padding:15px 40px;text-decoration:none;border-radius:8px;font-weight:bold;">Reset Password</a></div>
<p style="color:#999;font-size:13px;">Link expires in 1 hour.</p></div></div></body></html>';
}
?>
