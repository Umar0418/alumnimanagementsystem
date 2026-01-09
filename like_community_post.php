<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require "db.php";

$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$roll_no = isset($_POST['roll_no']) ? trim($_POST['roll_no']) : '';

if ($post_id <= 0 || empty($roll_no)) {
    echo json_encode(["status" => false, "message" => "Invalid parameters"]);
    exit;
}

// Check if already liked
$checkStmt = $conn->prepare("SELECT id FROM post_likes WHERE post_id = ? AND roll_no = ?");
$checkStmt->bind_param("is", $post_id, $roll_no);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Unlike
    $deleteStmt = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND roll_no = ?");
    $deleteStmt->bind_param("is", $post_id, $roll_no);
    $deleteStmt->execute();
    
    $updateStmt = $conn->prepare("UPDATE community_posts SET likes_count = likes_count - 1 WHERE id = ?");
    $updateStmt->bind_param("i", $post_id);
    $updateStmt->execute();
    
    echo json_encode(["status" => true, "message" => "Unliked", "is_liked" => false]);
} else {
    // Like
    $insertStmt = $conn->prepare("INSERT INTO post_likes (post_id, roll_no) VALUES (?, ?)");
    $insertStmt->bind_param("is", $post_id, $roll_no);
    $insertStmt->execute();
    
    $updateStmt = $conn->prepare("UPDATE community_posts SET likes_count = likes_count + 1 WHERE id = ?");
    $updateStmt->bind_param("i", $post_id);
    $updateStmt->execute();
    
    echo json_encode(["status" => true, "message" => "Liked", "is_liked" => true]);
}

$conn->close();
?>
