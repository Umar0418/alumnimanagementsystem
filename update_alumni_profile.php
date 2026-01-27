<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ob_start();

require "db.php";
ob_clean();

try {
    // Get form data
    $roll_no = $_POST['roll_no'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $company = $_POST['company'] ?? '';
    $location = $_POST['location'] ?? '';
    $department = $_POST['department'] ?? '';
    $batch_year = $_POST['batch_year'] ?? '';
    $degree = $_POST['degree'] ?? '';
    $cgpa = $_POST['cgpa'] ?? null;
    $linkedin = $_POST['linkedin'] ?? '';
    $interests = $_POST['interests'] ?? '';
    $address = $_POST['address'] ?? '';
    
    error_log("=== Update Alumni Profile ===");
    error_log("Roll No: $roll_no");
    error_log("Name: $name");
    error_log("Phone: $phone");
    error_log("Email: $email");
    error_log("Company: $company");
    error_log("Location: $location");
    error_log("Department: $department");
    error_log("Batch: $batch_year");
    error_log("Degree: $degree");
    error_log("CGPA: $cgpa");
    error_log("LinkedIn: $linkedin");
    error_log("Interests: $interests");
    error_log("Address: $address");
    error_log("FILES array: " . print_r($_FILES, true));
    
    if (empty($roll_no)) {
        error_log("ERROR: Roll number is empty");
        throw new Exception("Roll number is required");
    }
    
    if (empty($name)) {
        error_log("ERROR: Name is empty");
        throw new Exception("Name is required");
    }
    
    error_log("Validations passed, proceeding with update");
    
    // Handle profile image upload
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/profiles/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'application/octet-stream'];
        $file_type = $_FILES['profile_image']['type'];
        $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_type, $allowed_types) || in_array($file_extension, $allowed_extensions)) {
            if ($_FILES['profile_image']['size'] <= 5 * 1024 * 1024) {
                $new_filename = 'profile_' . $roll_no . '_' . time() . '.' . $file_extension;
                $target_file = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    $profile_image = $target_file;
                    error_log("Profile image uploaded: $target_file");
                } else {
                    throw new Exception("Failed to save image file");
                }
            } else {
                throw new Exception("Image too large (max 5MB)");
            }
        } else {
            throw new Exception("Invalid image type");
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    // Update users table with ALL profile fields including profile_image
    if ($profile_image !== null) {
        // Update with new profile image
        $sql_users = "UPDATE users SET 
                      name = ?, 
                      email = ?,
                      phone = ?,
                      department = ?, 
                      batch_number = ?,
                      degree = ?,
                      cgpa = ?,
                      address = ?,
                      interests = ?,
                      profile_image = ?
                      WHERE roll_no = ?";
        $stmt = $conn->prepare($sql_users);
        if (!$stmt) {
            throw new Exception("Failed to prepare users update: " . $conn->error);
        }
        $stmt->bind_param("sssssssssss", $name, $email, $phone, $department, $batch_year, $degree, $cgpa, $address, $interests, $profile_image, $roll_no);
    } else {
        // Update without changing profile image
        $sql_users = "UPDATE users SET 
                      name = ?, 
                      email = ?,
                      phone = ?,
                      department = ?, 
                      batch_number = ?,
                      degree = ?,
                      cgpa = ?,
                      address = ?,
                      interests = ?
                      WHERE roll_no = ?";
        $stmt = $conn->prepare($sql_users);
        if (!$stmt) {
            throw new Exception("Failed to prepare users update: " . $conn->error);
        }
        $stmt->bind_param("ssssssssss", $name, $email, $phone, $department, $batch_year, $degree, $cgpa, $address, $interests, $roll_no);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update users table: " . $stmt->error);
    }
    $affected_users = $stmt->affected_rows;
    error_log("Updated users table: $affected_users rows");
    $stmt->close();
    
    // Check if alumni_directory exists
    $check = $conn->prepare("SELECT id, profile_image FROM alumni_directory WHERE roll_no = ?");
    $check->bind_param("s", $roll_no);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing record
        $row = $result->fetch_assoc();
        $oldImage = $row['profile_image'];
        
        error_log("Updating existing alumni_directory record");
        
        if ($profile_image === null) {
            // No new image - keep existing
            $sql = "UPDATE alumni_directory SET 
                    name=?, company=?, location=?, department=?, batch_year=?, 
                    degree=?, cgpa=?, linkedin=?, interests=?, address=?
                    WHERE roll_no=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare alumni update: " . $conn->error);
            }
            $stmt->bind_param("ssssssdssss", $name, $company, $location, $department, 
                         $batch_year, $degree, $cgpa, $linkedin, $interests, $address, $roll_no);
        } else {
            // Delete old image
            if ($oldImage && file_exists($oldImage)) {
                unlink($oldImage);
                error_log("Deleted old image: $oldImage");
            }
            
            $sql = "UPDATE alumni_directory SET 
                    name=?, company=?, location=?, department=?, batch_year=?, 
                    degree=?, cgpa=?, linkedin=?, interests=?, address=?, profile_image=?
                    WHERE roll_no=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare alumni update with image: " . $conn->error);
            }
            $stmt->bind_param("ssssssdsssss", $name, $company, $location, $department, 
                         $batch_year, $degree, $cgpa, $linkedin, $interests, $address, 
                         $profile_image, $roll_no);
        }
    } else {
        // Insert new record
        error_log("Inserting new alumni_directory record");
        
        if ($profile_image === null) {
            $profile_image = '';
        }
        
        $sql = "INSERT INTO alumni_directory 
                (roll_no, name, company, location, department, batch_year, degree, cgpa, 
                 linkedin, interests, address, profile_image, mentorship) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'no')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare alumni insert: " . $conn->error);
        }
        $stmt->bind_param("ssssssdsssss", $roll_no, $name, $company, $location, $department, 
                     $batch_year, $degree, $cgpa, $linkedin, $interests, $address, $profile_image);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to save alumni data: " . $stmt->error);
    }
    
    $affected_alumni = $stmt->affected_rows;
    error_log("Updated alumni_directory: $affected_alumni rows");
    $stmt->close();
    $check->close();
    
    // Commit transaction
    $conn->commit();
    
    // Construct full URL for profile image
    $profile_image_url = null;
    if (!empty($profile_image)) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        $base_path = rtrim($base_path, '/');
        $relative_path = ltrim($profile_image, '/');
        
        if ($base_path === '' || $base_path === '.') {
            $profile_image_url = $protocol . "://" . $host . "/" . $relative_path;
        } else {
            $profile_image_url = $protocol . "://" . $host . $base_path . "/" . $relative_path;
        }
        
        error_log("Profile image URL: $profile_image_url");
    }
    
    error_log("Profile updated successfully for $roll_no");
    ob_end_clean();
    echo json_encode([
        "status" => true, 
        "message" => "Profile updated successfully",
        "profile_image" => $profile_image_url,
        "email" => $email,
        "debug" => [
            "users_updated" => $affected_users,
            "alumni_updated" => $affected_alumni,
            "image_path" => $profile_image
        ]
    ], JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    // Rollback on error
    if (isset($conn)) {
        $conn->rollback();
    }
    
    error_log("Profile update error: " . $e->getMessage());
    ob_end_clean();
    echo json_encode([
        "status" => false, 
        "message" => "Failed to update: " . $e->getMessage()
    ], JSON_UNESCAPED_SLASHES);
}

if (isset($conn)) {
    $conn->close();
}
?>
