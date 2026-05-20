<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

if (!$data) {
    $data = $_POST;
}

// Validate required fields
if (empty($data['school_code'])) {
    echo json_encode(['success' => false, 'message' => 'School code is required']);
    exit;
}

if (empty($data['school_name'])) {
    echo json_encode(['success' => false, 'message' => 'School name is required']);
    exit;
}

// Check if exists
$check = $conn->prepare("SELECT school_id FROM TX_SCHOOL_DETAILS WHERE school_code = ?");
$check->bind_param('s', $data['school_code']);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'School code already exists']);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Insert all fields
$sql = "INSERT INTO TX_SCHOOL_DETAILS (
            school_code, 
            school_name, 
            board_id, 
            address, 
            city, 
            state, 
            postal_code, 
            country_code, 
            contact_person, 
            contact_email, 
            contact_phone, 
            gst_number, 
            status, 
            created_dtm
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);

$school_code = strtoupper($data['school_code']);
$school_name = $data['school_name'];
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

$stmt->bind_param(
    'sssssssssssss',
    $school_code,
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
    $status
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'School created successfully',
        'school_id' => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>