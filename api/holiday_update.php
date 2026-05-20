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

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    $conn->close();
    exit;
}

$id = intval($data['id']);
$academic_year = $conn->real_escape_string($data['academic_year']);
$class_id = !empty($data['class_id']) ? intval($data['class_id']) : 'NULL';
$board_id = $conn->real_escape_string($data['board_id']);
$holiday_date = $data['holiday_date'];
$holiday_end_date = !empty($data['holiday_end_date']) ? "'" . $data['holiday_end_date'] . "'" : 'NULL';
$holiday_name = $conn->real_escape_string($data['holiday_name']);
$holiday_type = $data['holiday_type'];

$sql = "UPDATE TX_SCHOOL_HOLIDAYS SET 
        academic_year = '$academic_year',
        class_id = " . ($class_id == 'NULL' ? 'NULL' : $class_id) . ",
        board_id = '$board_id',
        holiday_date = '$holiday_date',
        holiday_end_date = $holiday_end_date,
        holiday_name = '$holiday_name',
        holiday_type = '$holiday_type',
        updated_dtm = NOW()
        WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Holiday updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>