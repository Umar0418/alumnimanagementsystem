<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors for clean JSON output
ini_set('display_errors', 0);
error_reporting(0);

require "db.php";

// Check if connection is valid
if (!$conn) {
    echo json_encode([
        "status" => false,
        "message" => "Database connection failed",
        "announcements" => []
    ]);
    exit;
}

// Accept usertype from GET, POST, or JSON body
$usertype = '';

// First check GET parameter (from @Query in Retrofit)
if (isset($_GET['usertype']) && !empty($_GET['usertype'])) {
    $usertype = trim($_GET['usertype']);
}
// Then check POST
elseif (isset($_POST['usertype']) && !empty($_POST['usertype'])) {
    $usertype = trim($_POST['usertype']);
}
// Finally check JSON body
else {
    $data = json_decode(file_get_contents("php://input"), true);
    if (is_array($data) && isset($data['usertype'])) {
        $usertype = trim($data['usertype']);
    }
}

// usertype is OPTIONAL for admin view
// If not provided, return ALL announcements
if ($usertype === '') {
    // Admin view - get all announcements
    $sql = "SELECT id, title, message, target, created_at FROM announcements ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    
    $announcements = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = [
                "id" => (int)$row['id'],
                "title" => $row['title'] ?? "",
                "message" => $row['message'] ?? "",
                "target" => $row['target'] ?? "",
                "created_at" => $row['created_at'] ?? ""
            ];
        }
    }
    
    echo json_encode([
        "status" => true,
        "announcements" => $announcements
    ]);
    
    $conn->close();
    exit;
}

/*
Allowed usertype values:
- alumni
- students
*/

// Modified query to also match 'both' and 'all' targets
$sql = "SELECT id, title, message, target, created_at 
        FROM announcements 
        WHERE target = ? OR target = 'both' OR target = 'all'
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usertype);
$stmt->execute();
$result = $stmt->get_result();

$announcements = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = [
            "id" => (int)$row['id'],
            "title" => $row['title'] ?? "",
            "message" => $row['message'] ?? "",
            "target" => $row['target'] ?? "",
            "created_at" => $row['created_at'] ?? ""
        ];
    }
}

$stmt->close();

echo json_encode([
    "status" => true,
    "announcements" => $announcements
]);

$conn->close();
?>
