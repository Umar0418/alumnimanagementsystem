<?php
/**
 * Email Configuration
 */

// Site URL - Update with your server URL
define('SITE_URL', 'http://localhost/alumni');

// Email Method: 2 = Brevo API, 3 = PHP mail()
define('EMAIL_METHOD', 3);

// Brevo settings (optional - for real email delivery)
define('BREVO_API_KEY', '');
define('BREVO_SENDER_EMAIL', 'your-email@gmail.com');
define('BREVO_SENDER_NAME', 'Alumni Management');
?>
