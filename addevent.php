<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors from appearing in output - they break JSON parsing
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffer to catch any stray output
ob_start();

require "db.php";

// Clean any output from db.php
ob_clean();

// Get form data
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$event_date  = trim($_POST['event_date'] ?? '');
$event_time  = trim($_POST['event_time'] ?? '');
$venue       = trim($_POST['venue'] ?? '');
$target_audience = trim($_POST['target_audience'] ?? '');

// Validate required fields
if ($title == "" || $event_date == "" || $event_time == "" || $venue == "") {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit;
}

// Format date and time if needed
if (strpos($event_date, '/') !== false) {
    $event_date = date('Y-m-d', strtotime($event_date));
}

// Handle file upload
$event_banner = '';
if (isset($_FILES['event_banner']) && $_FILES['event_banner']['error'] == 0) {
    $upload_dir = 'uploads/events/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Get file info
    $file_type = $_FILES['event_banner']['type'];
    $file_name = $_FILES['event_banner']['name'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Log for debugging
    error_log("Upload attempt - Type: $file_type, Extension: $file_extension, Name: $file_name");
    
    // Validate file type - check both MIME type and extension
    $allowed_mime_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'application/octet-stream'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Accept if either MIME type matches OR extension is valid (Android sometimes sends generic MIME types)
    if (in_array($file_type, $allowed_mime_types) || in_array($file_extension, $allowed_extensions)) {
        // Validate file size (max 5MB)
        if ($_FILES['event_banner']['size'] <= 5 * 1024 * 1024) {
            // Generate unique filename with proper extension
            $new_filename = 'event_' . time() . '_' . uniqid() . '.' . $file_extension;
            $target_file = $upload_dir . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['event_banner']['tmp_name'], $target_file)) {
                $event_banner = $target_file;
                error_log("File uploaded successfully: $target_file");
            } else {
                ob_end_clean();
                echo json_encode(["status" => false, "message" => "Failed to save uploaded file"]);
                exit;
            }
        } else {
            ob_end_clean();
            echo json_encode(["status" => false, "message" => "File size too large (max 5MB)"]);
            exit;
        }
    } else {
        ob_end_clean();
        error_log("Invalid file type rejected - MIME: $file_type, Extension: $file_extension");
        echo json_encode(["status" => false, "message" => "Invalid file type (MIME: $file_type). Only JPEG, PNG, GIF, and WebP images allowed"]);
        exit;
    }
}

// Insert into database
$sql = "INSERT INTO events (title, description, event_date, event_time, venue, event_banner, target_audience)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param("sssssss", $title, $description, $event_date, $event_time, $venue, $event_banner, $target_audience);

if ($stmt->execute()) {
    $newId = $conn->insert_id;
    ob_end_clean();
    echo json_encode([
        "status" => true, 
        "message" => "Event added successfully",
        "event_id" => $newId,
        "event_banner" => $event_banner
    ]);
} else {
    // If insert failed and file was uploaded, delete the file
    if ($event_banner != '' && file_exists($event_banner)) {
        unlink($event_banner);
    }
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Failed to add event: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
