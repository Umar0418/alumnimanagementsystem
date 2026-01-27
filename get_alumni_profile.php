<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Suppress errors
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require "db.php";
ob_clean();

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    $data = $_POST;
}

$roll_no = trim($data['roll_no'] ?? '');

if ($roll_no === '') {
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "roll_no is required"
    ]);
    exit;
}

/* First try to get from alumni_directory */
$profile = null;

$check = $conn->prepare(
    "SELECT roll_no, name, department, batch_year, degree, cgpa, company, location, 
            linkedin, profile_image, address, interests, mentorship
     FROM alumni_directory
     WHERE roll_no = ?"
);

if ($check) {
    $check->bind_param("s", $roll_no);
    $check->execute();
    $res = $check->get_result();
    
    if ($res->num_rows > 0) {
        $profile = $res->fetch_assoc();
    }
    $check->close();
}

/* If not in alumni_directory, create default profile from users table */
if (!$profile) {
    $userQ = $conn->prepare(
        "SELECT roll_no, name, department, batch_number, '' as company, '' as location, 'no' as mentorship
         FROM users
         WHERE roll_no = ? AND usertype = 'alumni'"
    );
    
    if ($userQ) {
        $userQ->bind_param("s", $roll_no);
        $userQ->execute();
        $userRes = $userQ->get_result();
        
        if ($userRes->num_rows > 0) {
            $userData = $userRes->fetch_assoc();
            // Create default profile structure
            $profile = [
                'roll_no' => $userData['roll_no'],
                'name' => $userData['name'] ?? '',
                'department' => $userData['department'] ?? '',
                'batch_year' => $userData['batch_number'] ?? '',
                'company' => '',
                'location' => '',
                'mentorship' => 'no',
                'linkedin' => null,
                'profile_image' => null
            ];
        }
        $userQ->close();
    }
}

if ($profile) {
    // Ensure all fields exist
    $profile['linkedin'] = $profile['linkedin'] ?? null;
    $profile['profile_image'] = $profile['profile_image'] ?? null;
    $profile['batch_year'] = $profile['batch_year'] ?? '';
    $profile['company'] = $profile['company'] ?? '';
    $profile['location'] = $profile['location'] ?? '';
    $profile['department'] = $profile['department'] ?? '';
    $profile['mentorship'] = $profile['mentorship'] ?? 'no';
    $profile['degree'] = $profile['degree'] ?? null;
    $profile['cgpa'] = $profile['cgpa'] ?? null;
    $profile['address'] = $profile['address'] ?? null;
    $profile['interests'] = $profile['interests'] ?? null;
    
    // Keep profile_image as relative path - app will prepend BASE_URL
    // Don't construct full URL here as $_SERVER['HTTP_HOST'] may be incorrect
    if (!empty($profile['profile_image'])) {
        error_log("Profile image path: " . $profile['profile_image']);
    }
    
    ob_end_clean();
    echo json_encode([
        "status" => true,
        "profile" => $profile
    ]);
} else {
    ob_end_clean();
    echo json_encode([
        "status" => false,
        "message" => "Profile not found. Please ensure you are logged in as an alumni user."
    ]);
}

$conn->close();
?>
