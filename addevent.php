<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$title           = trim($_POST['title'] ?? '');
$description     = trim($_POST['description'] ?? '');
$event_date      = trim($_POST['event_date'] ?? '');
$event_time      = trim($_POST['event_time'] ?? '');
$venue           = trim($_POST['venue'] ?? '');
$location        = trim($_POST['location'] ?? '');
$target_audience = trim($_POST['target_audience'] ?? 'both');
$banner          = '';

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
} else if (isset($_POST['banner'])) {
    // If banner is passed as a URL string (not file upload)
    $banner = trim($_POST['banner']);
}

// Validate required fields
if ($title == "" || $event_date == "" || $event_time == "" || $venue == "") {
    echo json_encode(["status" => false, "message" => "Required fields are missing: title, event_date, event_time, venue"]);
    exit;
}

// Validate target_audience enum
$validAudiences = ['students', 'alumni', 'both'];
if (!in_array($target_audience, $validAudiences)) {
    $target_audience = 'both';
}

$sql = "INSERT INTO events (title, description, event_date, event_time, venue, banner, location, target_audience)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => false, "message" => "SQL prepare error: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssssssss", $title, $description, $event_date, $event_time, $venue, $banner, $location, $target_audience);

if ($stmt->execute()) {
    $event_id = $conn->insert_id;
    echo json_encode([
        "status" => true, 
        "message" => "Event added successfully",
        "event_id" => $event_id,
        "banner" => $banner
    ]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to add event: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
