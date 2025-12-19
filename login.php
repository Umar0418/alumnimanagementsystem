<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

/* Get login data */
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

/* Validation */
if ($email == "" || $password == "") {
    echo json_encode([
        "status" => false,
        "message" => "Email and password are required"
    ]);
    exit;
}

/* Check user in users table */
$sql = "SELECT id, name, email, phone, usertype, password 
        FROM users 
        WHERE email = ? AND password = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    echo json_encode([
        "status" => true,
        "message" => "Login successful",
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "phone" => $user['phone'],
            "usertype" => $user['usertype']   // student / alumni / admin
        ]
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "Invalid email or password"
    ]);
}
?>
