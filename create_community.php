<?php
header("Content-Type: application/json");
require "db.php";

$roll_no = $_POST['roll_no'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';

if ($roll_no == '' || $name == '') {
    echo json_encode([
        "status" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

// Check if community with same name exists
$checkExisting = $conn->prepare("SELECT id FROM communities WHERE name = ?");
$checkExisting->bind_param("s", $name);
$checkExisting->execute();
$checkExisting->store_result();

if ($checkExisting->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "A community with this name already exists"
    ]);
    exit;
}

// Insert new community
$sql = "INSERT INTO communities (name, description, created_by, member_count, created_at) 
        VALUES (?, ?, ?, 1, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $description, $roll_no);

if ($stmt->execute()) {
    $communityId = $conn->insert_id;
    
    // Add creator as member
    $memberSql = "INSERT INTO community_members (community_id, roll_no, joined_at) VALUES (?, ?, NOW())";
    $memberStmt = $conn->prepare($memberSql);
    $memberStmt->bind_param("is", $communityId, $roll_no);
    $memberStmt->execute();
    
    echo json_encode([
        "status" => true,
        "message" => "Community created successfully!"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to create community: " . $conn->error
    ]);
}

$conn->close();
?>
