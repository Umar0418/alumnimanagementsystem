<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "db.php";

$alumni = [];

/*
|--------------------------------------------------------------------------
| 1. Fetch alumni from alumni_directory (primary source)
|--------------------------------------------------------------------------
*/
$sql = "SELECT 
            roll_no,
            name,
            department,
            batch_year,
            company,
            location,
            mentorship
        FROM alumni_directory
        ORDER BY name ASC";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $alumni[$row['roll_no']] = [
            "roll_no"     => $row['roll_no'],
            "name"        => $row['name'],
            "department"  => $row['department'],
            "batch_year"  => $row['batch_year'],
            "company"     => $row['company'],
            "location"    => $row['location'],
            "mentorship"  => (int)$row['mentorship']
        ];
    }
}

/*
|--------------------------------------------------------------------------
| 2. Fetch alumni from users table and merge missing ones
|--------------------------------------------------------------------------
*/
$sql2 = "SELECT roll_no, name 
         FROM users 
         WHERE usertype = 'alumni'
         ORDER BY name ASC";

$result2 = $conn->query($sql2);

if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        if (!isset($alumni[$row['roll_no']])) {
            $alumni[$row['roll_no']] = [
                "roll_no"     => $row['roll_no'],
                "name"        => $row['name'],
                "department"  => "",
                "batch_year"  => "",
                "company"     => "",
                "location"    => "",
                "mentorship"  => 0
            ];
        }
    }
}

/*
|--------------------------------------------------------------------------
| 3. Final JSON output
|--------------------------------------------------------------------------
*/
echo json_encode([
    "status" => true,
    "count"  => count($alumni),
    "alumni" => array_values($alumni)
]);

$conn->close();
?>
