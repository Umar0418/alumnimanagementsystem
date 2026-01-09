<?php
/**
 * Email Helper using PHPMailer
 * Handles all email sending for the application
 */

require_once 'email_config.php';

// Include PHPMailer classes
// Download from: https://github.com/PHPMailer/PHPMailer
require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send email using PHPMailer with Gmail SMTP
 * 
 * @param string $toEmail Recipient email address
 * @param string $toName Recipient name
 * @param string $subject Email subject
 * @param string $htmlBody HTML email body
 * @return array ['success' => bool, 'message' => string]
 */
function sendEmail($toEmail, $toName, $subject, $htmlBody) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = SMTP_DEBUG;
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags($htmlBody);
        
        $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully'];
        
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return ['success' => false, 'message' => $mail->ErrorInfo];
    }
}

/**
 * Send password reset email
 * 
 * @param string $email Recipient email
 * @param string $name User's name
 * @param string $token Reset token
 * @return array ['success' => bool, 'message' => string]
 */
function sendPasswordResetEmail($email, $name, $token) {
    $resetLink = SITE_URL . "/reset_password.php?token=" . $token;
    
    $subject = "Password Reset - Alumni Management";
    
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, sans-serif; background-color: #f8fafc;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                        <!-- Header -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); padding: 40px 40px 30px; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">Password Reset</h1>
                            </td>
                        </tr>
                        <!-- Content -->
                        <tr>
                            <td style="padding: 40px;">
                                <p style="color: #1e293b; font-size: 16px; margin: 0 0 20px;">Hello <strong>' . htmlspecialchars($name) . '</strong>,</p>
                                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">We received a request to reset your password for your Alumni Management account. Click the button below to create a new password:</p>
                                
                                <!-- Button -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding: 10px 0 30px;">
                                            <a href="' . $resetLink . '" style="display: inline-block; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 16px; font-weight: 600;">Reset Password</a>
                                        </td>
                                    </tr>
                                </table>
                                
                                <p style="color: #64748b; font-size: 14px; margin: 0 0 10px;">Or copy this link into your browser:</p>
                                <p style="color: #3b82f6; font-size: 13px; word-break: break-all; background: #f1f5f9; padding: 12px; border-radius: 8px; margin: 0 0 30px;">' . $resetLink . '</p>
                                
                                <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 10px;">
                                    <p style="color: #94a3b8; font-size: 13px; margin: 0;">⏰ This link will expire in 1 hour.</p>
                                    <p style="color: #94a3b8; font-size: 13px; margin: 10px 0 0;">If you did not request this password reset, please ignore this email or contact support if you have concerns.</p>
                                </div>
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                                <p style="color: #94a3b8; font-size: 12px; margin: 0;">© ' . date('Y') . ' Alumni Management System</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    
    return sendEmail($email, $name, $subject, $htmlBody);
}
?>
