<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

// Get recent activity from various tables
$activities = [];

// Recent events - use event_date if created_at doesn't exist
$sql = "SELECT 'event' as type, title, event_date as activity_date FROM events ORDER BY event_date DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            "type" => "event",
            "description" => "New event: " . $row['title'],
            "time" => $row['activity_date'] ?? date('Y-m-d')
        ];
    }
}

// Recent job postings
$sql = "SELECT 'job' as type, title, posted_date as activity_date FROM jobs ORDER BY posted_date DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            "type" => "job",
            "description" => "New job: " . $row['title'],
            "time" => $row['activity_date'] ?? date('Y-m-d')
        ];
    }
}

// Recent announcements
$sql = "SELECT 'announcement' as type, title, created_at as activity_date FROM announcements ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            "type" => "announcement",
            "description" => "Announcement: " . $row['title'],
            "time" => $row['activity_date'] ?? date('Y-m-d')
        ];
    }
}

// Recent user registrations
$sql = "SELECT 'user' as type, name, usertype, created_at as activity_date FROM users ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userType = ucfirst($row['usertype'] ?? 'user');
        $activities[] = [
            "type" => "user",
            "description" => "New $userType: " . $row['name'],
            "time" => $row['activity_date'] ?? date('Y-m-d')
        ];
    }
}

// Sort by time descending
usort($activities, function($a, $b) {
    $timeA = strtotime($a['time']) ?: 0;
    $timeB = strtotime($b['time']) ?: 0;
    return $timeB - $timeA;
});

// Limit to 10 most recent
$activities = array_slice($activities, 0, 10);

echo json_encode($activities);
$conn->close();
?>
