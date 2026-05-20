<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    $conn->close();
    exit;
}

// Validate required fields
$required = ['school_id', 'academic_year', 'board_id', 'holiday_date', 'holiday_name', 'holiday_type'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        $conn->close();
        exit;
    }
}

$school_id = intval($data['school_id']);
$academic_year = $conn->real_escape_string($data['academic_year']);
$class_id = !empty($data['class_id']) ? intval($data['class_id']) : 'NULL';
$board_id = $conn->real_escape_string($data['board_id']);
$holiday_date = $data['holiday_date'];
$holiday_end_date = !empty($data['holiday_end_date']) ? "'" . $data['holiday_end_date'] . "'" : 'NULL';
$holiday_name = $conn->real_escape_string($data['holiday_name']);
$holiday_type = $data['holiday_type'];

$sql = "INSERT INTO TX_SCHOOL_HOLIDAYS (school_id, academic_year, class_id, board_id, holiday_date, holiday_end_date, holiday_name, holiday_type, created_dtm) 
        VALUES ($school_id, '$academic_year', " . ($class_id == 'NULL' ? 'NULL' : $class_id) . ", '$board_id', '$holiday_date', $holiday_end_date, '$holiday_name', '$holiday_type', NOW())";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Holiday created successfully', 'id' => $conn->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>