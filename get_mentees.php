<?php
header("Content-Type: application/json");
require "db.php";

// Get mentor roll number
$mentor_roll_no = $_POST['mentor_roll_no'] ?? '';

if ($mentor_roll_no == '') {
    echo json_encode([
        "status" => false,
        "message" => "mentor_roll_no is required"
    ]);
    exit;
}

// Fetch mentee requests for this mentor
$sql = "SELECT 
            mr.id,
            mr.roll_no AS student_id,
            u.name,
            s.department,
            s.graduation_year AS year,
            mr.topic,
            mr.status,
            u.email,
            u.phone
        FROM mentee_requests mr
        INNER JOIN users u ON mr.roll_no = u.roll_no
        LEFT JOIN students s ON mr.roll_no = s.roll_no
        WHERE mr.mentor_roll_no = ?
        ORDER BY 
            CASE mr.status
                WHEN 'pending' THEN 1
                WHEN 'active' THEN 2
                WHEN 'declined' THEN 3
                ELSE 4
            END,
            mr.created_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $mentor_roll_no);
$stmt->execute();

$result = $stmt->get_result();
$mentees = [];

while ($row = $result->fetch_assoc()) {
    // Provide defaults for optional fields
    $mentees[] = [
        "id" => (int)$row['id'],
        "student_id" => $row['student_id'],
        "name" => $row['name'] ?? "Unknown Student",
        "department" => $row['department'] ?? "Not specified",
        "year" => $row['year'] ?? "",
        "topic" => $row['topic'] ?? "General Mentorship",
        "status" => $row['status'],
        "email" => $row['email'] ?? null,
        "phone" => $row['phone'] ?? null
    ];
}

$stmt->close();
$conn->close();

echo json_encode([
    "status" => true,
    "mentees" => $mentees
]);
?>
