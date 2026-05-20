<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 to prevent HTML errors in JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get POST data
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data || empty($data['school_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

// Update query - only update fields that exist
$sql = "UPDATE TX_SCHOOL_DETAILS SET 
            school_name = ?,
            board_id = ?,
            address = ?,
            city = ?,
            state = ?,
            postal_code = ?,
            country_code = ?,
            contact_person = ?,
            contact_email = ?,
            contact_phone = ?,
            gst_number = ?,
            status = ?,
            updated_dtm = NOW()
        WHERE school_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$school_name = $data['school_name'] ?? '';
$board_id = $data['board_id'] ?? 'C';
$address = $data['address'] ?? '';
$city = $data['city'] ?? '';
$state = $data['state'] ?? '';
$postal_code = $data['postal_code'] ?? '';
$country_code = $data['country_code'] ?? 'IN';
$contact_person = $data['contact_person'] ?? '';
$contact_email = $data['contact_email'] ?? '';
$contact_phone = $data['contact_phone'] ?? '';
$gst_number = $data['gst_number'] ?? '';
$status = $data['status'] ?? 'A';
$school_id = $data['school_id'];

$stmt->bind_param(
    'ssssssssssssi',
    $school_name,
    $board_id,
    $address,
    $city,
    $state,
    $postal_code,
    $country_code,
    $contact_person,
    $contact_email,
    $contact_phone,
    $gst_number,
    $status,
    $school_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'School updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>