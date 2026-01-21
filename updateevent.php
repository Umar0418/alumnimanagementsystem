<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$id              = $_POST['id'] ?? '';
$title           = $_POST['title'] ?? '';
$description     = $_POST['description'] ?? '';
$event_date      = $_POST['event_date'] ?? '';
$event_time      = $_POST['event_time'] ?? '';
$venue           = $_POST['venue'] ?? '';
$location        = $_POST['location'] ?? '';
$target_audience = $_POST['target_audience'] ?? 'both';
$banner          = $_POST['banner'] ?? '';

// Handle banner upload
if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
    $uploadDir = 'uploads/events/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($_FILES['banner']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['banner']['tmp_name'], $targetPath)) {
        $banner = $targetPath;
    }
}

if ($id == "") {
    echo json_encode(["status" => false, "message" => "Event ID missing"]);
    exit;
}

// Validate target_audience enum
$validAudiences = ['students', 'alumni', 'both'];
if (!in_array($target_audience, $validAudiences)) {
    $target_audience = 'both';
}

$sql = "UPDATE events SET
        title = ?,
        description = ?,
        event_date = ?,
        event_time = ?,
        venue = ?,
        banner = ?,
        location = ?,
        target_audience = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => false, "message" => "SQL prepare error: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssssi",
    $title,
    $description,
    $event_date,
    $event_time,
    $venue,
    $banner,
    $location,
    $target_audience,
    $id
);

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "status" => true,
        "message" => "Event updated successfully",
        "banner" => $banner
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No event updated (check ID or values): " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
