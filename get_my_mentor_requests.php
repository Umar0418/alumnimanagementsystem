<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

require "db.php";

$student_roll_no = isset($_POST['roll_no']) ? trim($_POST['roll_no']) : '';

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS mentee_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50) NOT NULL,
    mentor_roll_no VARCHAR(50) NOT NULL,
    topic TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// If no roll_no, return all requests for debugging
if ($student_roll_no === "") {
    $result = $conn->query("SELECT * FROM mentee_requests ORDER BY id DESC LIMIT 10");
    $all = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $all[] = $row;
        }
    }
    echo json_encode(array(
        "status" => true,
        "requests" => array(),
        "debug_all_requests" => $all,
        "message" => "No roll_no provided, showing debug info"
    ));
    exit;
}

// Get requests for this student
$sql = "SELECT id, mentor_roll_no, topic, status, created_at FROM mentee_requests WHERE roll_no = '" . $conn->real_escape_string($student_roll_no) . "' ORDER BY id DESC";

$result = $conn->query($sql);

$requests = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get mentor name
        $mentor_name = "Mentor";
        $nameResult = $conn->query("SELECT name FROM users WHERE roll_no = '" . $conn->real_escape_string($row['mentor_roll_no']) . "'");
        if ($nameResult && $nameResult->num_rows > 0) {
            $nameRow = $nameResult->fetch_assoc();
            $mentor_name = $nameRow['name'];
        }
        
        $requests[] = array(
            "id" => intval($row['id']),
            "mentor_roll_no" => strval($row['mentor_roll_no']),
            "mentor_name" => strval($mentor_name),
            "mentor_field" => "",
            "topic" => strval($row['topic']),
            "status" => strval($row['status']),
            "requested_at" => strval($row['created_at'])
        );
    }
}

echo json_encode(array(
    "status" => true,
    "requests" => $requests,
    "debug_roll_no" => $student_roll_no,
    "debug_count" => count($requests)
));

$conn->close();
?>
