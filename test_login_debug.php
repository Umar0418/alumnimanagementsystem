<?php
// Simple login test script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...<br>";

require "db.php";

echo "Database connection successful!<br>";

// Test we can query users table
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetch_assoc();
echo "Users table has " . $row['count'] . " records<br>";

// Test we can query admins table
$result = $conn->query("SELECT COUNT(*) as count FROM admins");
$row = $result->fetch_assoc();
echo "Admins table has " . $row['count'] . " records<br>";

echo "<br>All tests passed!";

$conn->close();
?>
