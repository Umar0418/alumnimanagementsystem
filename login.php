<?php
header("Content-Type: application/json; charset=UTF-8");
require "db.php";

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === "" || $password === "") {
    echo json_encode(["status" => false, "message" => "Email and password are required"]);
    exit;
}

// THIS IS THE CRITICAL CHANGE: Ensure roll_no is in the SELECT statement
$sql = "SELECT id, roll_no, name, email, phone, usertype, password
        FROM users
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if ($user['password'] === $password) {
        echo json_encode([
            "status" => true,
            "message" => "Login successful",
            "user" => [
                "id" => $user['id'],
                "roll_no" => $user['roll_no'], // AND THIS IS THE CRITICAL CHANGE
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "usertype" => $user['usertype']
            ]
        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Incorrect password"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "User not found"]);
}
?>