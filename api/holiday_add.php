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

// Check if school has this board
$board_check_sql = "SELECT board_id FROM TX_SCHOOL_DETAILS WHERE school_id = $school_id";
$board_result = $conn->query($board_check_sql);
if ($board_result && $board_result->num_rows > 0) {
    $school_data = $board_result->fetch_assoc();
    $school_board = $school_data['board_id'];
    $mapped_board = '';
    if ($school_board == 'C' || $school_board == 'CBSE') $mapped_board = 'C';
    else if ($school_board == 'I' || $school_board == 'ICSE') $mapped_board = 'I';
    else if ($school_board == 'W' || $school_board == 'WBBSE') $mapped_board = 'W';
    else $mapped_board = $school_board;
    
    if ($mapped_board != $board_id) {
        echo json_encode(['success' => false, 'message' => 'Board mismatch! This school is associated with ' . $school_board . ' board.']);
        $conn->close();
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'School not found']);
    $conn->close();
    exit;
}

// Check academic year
$current_year = date('Y');
if (intval($academic_year) < $current_year) {
    echo json_encode(['success' => false, 'message' => 'Cannot create holiday for past academic year.']);
    $conn->close();
    exit;
}

// Check duplicate
$duplicate_check = "SELECT id FROM TX_SCHOOL_HOLIDAYS 
                    WHERE school_id = $school_id 
                    AND (is_deleted = 0 OR is_deleted IS NULL)
                    AND holiday_date = '$holiday_date'
                    AND holiday_name = '$holiday_name'";
$dup_result = $conn->query($duplicate_check);
if ($dup_result && $dup_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Duplicate holiday! This holiday already exists.']);
    $conn->close();
    exit;
}

$sql = "INSERT INTO TX_SCHOOL_HOLIDAYS (school_id, academic_year, class_id, board_id, holiday_date, holiday_end_date, holiday_name, holiday_type, created_dtm) 
        VALUES ($school_id, '$academic_year', " . ($class_id == 'NULL' ? 'NULL' : $class_id) . ", '$board_id', '$holiday_date', $holiday_end_date, '$holiday_name', '$holiday_type', NOW())";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Holiday created successfully', 'id' => $conn->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>