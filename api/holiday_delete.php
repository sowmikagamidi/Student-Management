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

// Get ID from JSON input
$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data - ID is required']);
    $conn->close();
    exit;
}

$id = intval($data['id']);

// Get holiday details
$holiday_sql = "SELECT school_id, holiday_date, holiday_end_date, class_id FROM TX_SCHOOL_HOLIDAYS WHERE id = $id AND (is_deleted = 0 OR is_deleted IS NULL)";
$holiday_result = $conn->query($holiday_sql);

if ($holiday_result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Holiday not found']);
    $conn->close();
    exit;
}

$holiday = $holiday_result->fetch_assoc();
$holiday_date = $holiday['holiday_date'];
$holiday_end_date = $holiday['holiday_end_date'];
$school_id = $holiday['school_id'];
$class_id = $holiday['class_id'];

// Check for attendance records - using DATE() to ignore time portion
$attendance_check = "";
if ($holiday_end_date && $holiday_end_date != '0000-00-00' && $holiday_end_date != '') {
    // Date range holiday
    $attendance_check = "SELECT COUNT(*) as count FROM TX_STUDENT_ATTENDANCE 
                         WHERE school_id = $school_id 
                         AND DATE(entry_dtm) BETWEEN '$holiday_date' AND '$holiday_end_date'";
} else {
    // Single day holiday
    $attendance_check = "SELECT COUNT(*) as count FROM TX_STUDENT_ATTENDANCE 
                         WHERE school_id = $school_id 
                         AND DATE(entry_dtm) = '$holiday_date'";
}

$attendance_result = $conn->query($attendance_check);

if (!$attendance_result) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    $conn->close();
    exit;
}

$attendance_count = $attendance_result->fetch_assoc()['count'];

if ($attendance_count > 0) {
    echo json_encode([
        'success' => false, 
        'message' => "Cannot delete this holiday. $attendance_count attendance record(s) exist for this date. Please delete attendance records first."
    ]);
    $conn->close();
    exit;
}

// Delete the holiday
$sql = "UPDATE TX_SCHOOL_HOLIDAYS SET is_deleted = 1 WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Holiday deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>