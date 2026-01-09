<?php
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";

/* Read roll_no */
$roll_no = $_POST['roll_no'] ?? '';

if ($roll_no === '') {
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* Correct JOIN using your table structure */
$sql = "SELECT
            e.id AS event_id,
            e.title AS event_name,
            e.description,
            e.event_date,
            e.event_time,
            e.venue,
            er.registered_at
        FROM event_registrations er
        INNER JOIN events e ON er.event_id = e.id
        WHERE er.roll_no = ?
        ORDER BY e.event_date DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "SQL prepare failed",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $roll_no);
$stmt->execute();
$result = $stmt->get_result();

$my_events = [];

while ($row = $result->fetch_assoc()) {
    $my_events[] = [
        "event_id"      => (int)$row['event_id'],
        "event_name"    => $row['event_name'],
        "description"   => $row['description'],
        "event_date"    => $row['event_date'],
        "event_time"    => $row['event_time'],
        "venue"         => $row['venue'],
        "registered_at" => $row['registered_at']
    ];
}

echo json_encode([
    "status" => true,
    "my_events" => $my_events
]);
