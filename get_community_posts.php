<?php
header("Content-Type: application/json");
require "db.php";

$community_id = $_POST['community_id'] ?? 0;
$roll_no = $_POST['roll_no'] ?? '';

if ($community_id == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid community ID"
    ]);
    exit;
}

// Get all posts for the community with user info
$sql = "SELECT 
            p.id, 
            p.content, 
            p.likes_count, 
            p.comments_count, 
            p.created_at,
            u.name as user_name,
            p.roll_no as user_roll_no,
            (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND roll_no = ?) as is_liked
        FROM community_posts p
        LEFT JOIN users u ON p.roll_no = u.roll_no
        WHERE p.community_id = ?
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $roll_no, $community_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $row['is_liked'] = $row['is_liked'] > 0;
    $row['likes_count'] = (int) $row['likes_count'];
    $row['comments_count'] = (int) $row['comments_count'];
    $posts[] = $row;
}

echo json_encode([
    "status" => true,
    "posts" => $posts
]);

$conn->close();
?>
