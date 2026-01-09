<?php
header("Content-Type: application/json");
require "db.php";

$post_id = $_POST['post_id'] ?? 0;
$roll_no = $_POST['roll_no'] ?? '';

if ($post_id == 0 || $roll_no == '') {
    echo json_encode([
        "status" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

// Check if already liked
$checkLike = $conn->prepare("SELECT id FROM post_likes WHERE post_id = ? AND roll_no = ?");
$checkLike->bind_param("is", $post_id, $roll_no);
$checkLike->execute();
$checkLike->store_result();

if ($checkLike->num_rows > 0) {
    // Unlike - remove like
    $deleteLike = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND roll_no = ?");
    $deleteLike->bind_param("is", $post_id, $roll_no);
    $deleteLike->execute();

    // Decrease like count
    $conn->query("UPDATE community_posts SET likes_count = likes_count - 1 WHERE id = $post_id AND likes_count > 0");

    echo json_encode([
        "status" => true,
        "message" => "Post unliked",
        "liked" => false
    ]);
} else {
    // Like - add like
    $insertLike = $conn->prepare("INSERT INTO post_likes (post_id, roll_no, created_at) VALUES (?, ?, NOW())");
    $insertLike->bind_param("is", $post_id, $roll_no);
    $insertLike->execute();

    // Increase like count
    $conn->query("UPDATE community_posts SET likes_count = likes_count + 1 WHERE id = $post_id");

    echo json_encode([
        "status" => true,
        "message" => "Post liked",
        "liked" => true
    ]);
}

$conn->close();
?>
