<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors from appearing in output
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require "db.php";
ob_clean();

$id           = trim($_POST['id'] ?? '');
$title        = trim($_POST['title'] ?? '');
$description  = trim($_POST['description'] ?? '');
$event_date   = trim($_POST['event_date'] ?? '');
$event_time   = trim($_POST['event_time'] ?? '');
$venue        = trim($_POST['venue'] ?? '');
$target_audience = trim($_POST['target_audience'] ?? '');

// Log received data for debugging
error_log("Update Event - ID: $id, Title: $title, Has File: " . (isset($_FILES['event_banner']) ? 'yes' : 'no'));

if ($id == "") {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Event ID missing"]);
    exit;
}

// Format date if needed
if (strpos($event_date, '/') !== false) {
    $event_date = date('Y-m-d', strtotime($event_date));
}

// Handle image upload if a new file is provided
$event_banner = '';
$uploadedNewImage = false;

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
    
    // Accept if either MIME type matches OR extension is valid
    if (in_array($file_type, $allowed_mime_types) || in_array($file_extension, $allowed_extensions)) {
        // Validate file size (max 5MB)
        if ($_FILES['event_banner']['size'] <= 5 * 1024 * 1024) {
            // Get old image path to delete later
            $old_image = '';
            $stmt_old = $conn->prepare("SELECT event_banner FROM events WHERE id = ?");
            if ($stmt_old) {
                $stmt_old->bind_param("i", $id);
                $stmt_old->execute();
                $result_old = $stmt_old->get_result();
                if ($row = $result_old->fetch_assoc()) {
                    $old_image = $row['event_banner'];
                }
                $stmt_old->close();
            }
            
            // Generate unique filename with proper extension
            $new_filename = 'event_' . time() . '_' . uniqid() . '.' . $file_extension;
            $target_file = $upload_dir . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['event_banner']['tmp_name'], $target_file)) {
                $event_banner = $target_file;
                $uploadedNewImage = true;
                error_log("File uploaded successfully: $target_file");
                
                // Delete old image if it exists and is different
                if ($old_image != '' && $old_image != $target_file && file_exists($old_image)) {
                    unlink($old_image);
                    error_log("Deleted old image: $old_image");
                }
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
        echo json_encode(["status" => false, "message" => "Invalid file type. Only JPEG, PNG, GIF, and WebP images allowed"]);
        exit;
    }
}

// Build dynamic update query
$updates = [];
$params = [];
$types = "";

if ($title != "") {
    $updates[] = "title = ?";
    $params[] = $title;
    $types .= "s";
}
if ($description != "") {
    $updates[] = "description = ?";
    $params[] = $description;
    $types .= "s";
}
if ($event_date != "") {
    $updates[] = "event_date = ?";
    $params[] = $event_date;
    $types .= "s";
}
if ($event_time != "") {
    $updates[] = "event_time = ?";
    $params[] = $event_time;
    $types .= "s";
}
if ($venue != "") {
    $updates[] = "venue = ?";
    $params[] = $venue;
    $types .= "s";
}
// Only update event_banner if a new image was uploaded
if ($uploadedNewImage && $event_banner != "") {
    $updates[] = "event_banner = ?";
    $params[] = $event_banner;
    $types .= "s";
}
if ($target_audience != "") {
    $updates[] = "target_audience = ?";
    $params[] = $target_audience;
    $types .= "s";
}

if (empty($updates)) {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "No fields to update"]);
    exit;
}

// Add id to params
$params[] = $id;
$types .= "i";

$sql = "UPDATE events SET " . implode(", ", $updates) . " WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    echo json_encode(["status" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();

// Check if the query was successful
if ($stmt->errno == 0) {
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "message" => "Event updated successfully",
        "affected_rows" => $stmt->affected_rows,
        "event_banner" => $uploadedNewImage ? $event_banner : null
    ]);
} else {
    // If update failed and a new image was uploaded, delete it
    if ($uploadedNewImage && $event_banner != '' && file_exists($event_banner)) {
        unlink($event_banner);
    }
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "Failed to update event: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
