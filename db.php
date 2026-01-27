<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "alumnidata";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    header("Content-Type: application/json");
    echo json_encode([
        "status" => false,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}
?>