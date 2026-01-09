<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Log - Alumni Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .subtitle {
            color: rgba(255,255,255,0.8);
            text-align: center;
            margin-bottom: 30px;
        }
        .email-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .email-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E7EB;
        }
        .email-to {
            font-size: 18px;
            font-weight: 600;
            color: #1E40AF;
        }
        .email-time {
            color: #6B7280;
            font-size: 14px;
        }
        .email-subject {
            font-size: 16px;
            color: #374151;
            margin-bottom: 16px;
        }
        .email-link {
            background: #F3F4F6;
            padding: 12px 16px;
            border-radius: 8px;
            word-break: break-all;
            font-size: 13px;
            color: #1E40AF;
            margin-bottom: 16px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .status {
            display: inline-block;
            background: #D1FAE5;
            color: #059669;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .no-emails {
            background: white;
            border-radius: 16px;
            padding: 60px;
            text-align: center;
            color: #6B7280;
        }
        .refresh-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .refresh-btn a {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Log</h1>
        <p class="subtitle">All password reset emails sent by the system</p>
        
        <?php
        $logFile = __DIR__ . '/email_log.json';
        $emails = [];
        
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $emails = json_decode($content, true) ?: [];
            $emails = array_reverse($emails); // Show newest first
        }
        
        if (empty($emails)) {
            echo '<div class="no-emails">
                <h2>No emails sent yet</h2>
                <p>Use the "Forgot Password" feature in the app to send a reset email.</p>
            </div>';
        } else {
            foreach ($emails as $email) {
                echo '<div class="email-card">
                    <div class="email-header">
                        <div>
                            <div class="email-to">To: ' . htmlspecialchars($email['to_email']) . '</div>
                            <div style="color:#6B7280;font-size:14px;">Name: ' . htmlspecialchars($email['to_name']) . '</div>
                        </div>
                        <div style="text-align:right;">
                            <span class="status">✓ Sent</span>
                            <div class="email-time">' . htmlspecialchars($email['sent_at']) . '</div>
                        </div>
                    </div>
                    <div class="email-subject"><strong>Subject:</strong> ' . htmlspecialchars($email['subject']) . '</div>
                    <div class="email-link"><strong>Reset Link:</strong><br>' . htmlspecialchars($email['reset_link']) . '</div>
                    <a href="' . htmlspecialchars($email['reset_link']) . '" class="btn" target="_blank">Open Reset Link</a>
                </div>';
            }
        }
        ?>
        
        <div class="refresh-btn">
            <a href="view_emails.php">🔄 Refresh</a>
        </div>
    </div>
</body>
</html>
