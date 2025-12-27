<?php
require "db.php";

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("
    SELECT roll_no FROM password_resets
    WHERE reset_token=? AND expires_at > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Invalid or expired reset link");
}
?>

<form method="POST" action="update_password.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
