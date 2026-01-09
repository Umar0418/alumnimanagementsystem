<?php
header("Content-Type: application/json");
require "db.php";

$community_id = $_POST['community_id'] ?? 0;
$roll_no = $_POST['roll_no'] ?? '';
$content = $_POST['content'] ?? '';

if ($community_id == 0 || $roll_no == '' || $content == '') {
    echo json_encode([
        "status" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

// Insert new post
$sql = "INSERT INTO community_posts (community_id, roll_no, content, likes_count, comments_count, created_at)
        VALUES (?, ?, ?, 0, 0, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $community_id, $roll_no, $content);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Post created successfully!"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to create post: " . $conn->error
    ]);
}

$conn->close();
?>
