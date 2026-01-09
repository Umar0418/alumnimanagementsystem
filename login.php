<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

ob_start();

require "db.php";

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === "" || $password === "") {
    echo json_encode(["status" => false, "message" => "Email and password are required"]);
    exit;
}

<<<<<<< HEAD
// THIS IS THE CRITICAL CHANGE: Ensure roll_no is in the SELECT statement
$sql = "SELECT id, roll_no, name, email, phone, usertype, password
=======
// Select all needed fields including student-specific fields
$sql = "SELECT id, roll_no, name, email, phone, usertype, password, department, year, address, cgpa, interests
>>>>>>> f1faf11f584ac842cd937ffc9bf01ed330e2c2fd
        FROM users
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if ($user['password'] === $password) {
        $stmt->close();
        
        echo json_encode([
            "status" => true,
            "message" => "Login successful",
            "user" => [
<<<<<<< HEAD
                "id" => $user['id'],
                "roll_no" => $user['roll_no'], // AND THIS IS THE CRITICAL CHANGE
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "usertype" => $user['usertype']
=======
                "id" => (int)$user['id'],
                "roll_no" => $user['roll_no'] ?? "",
                "name" => $user['name'] ?? "",
                "email" => $user['email'] ?? "",
                "phone" => $user['phone'] ?? "",
                "usertype" => $user['usertype'] ?? "",
                "department" => $user['department'] ?? "",
                "year" => $user['year'] ?? "",
                "address" => $user['address'] ?? "",
                "cgpa" => $user['cgpa'] ?? "",
                "interests" => $user['interests'] ?? ""
>>>>>>> f1faf11f584ac842cd937ffc9bf01ed330e2c2fd
            ]
        ]);
    } else {
        $stmt->close();
        echo json_encode(["status" => false, "message" => "Incorrect password"]);
    }
} else {
    $stmt->close();
    echo json_encode(["status" => false, "message" => "User not found"]);
}

ob_end_flush();
$conn->close();
?>