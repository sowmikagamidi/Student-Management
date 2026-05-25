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

// Get existing holiday details
$old_holiday_sql = "SELECT school_id, holiday_date, holiday_end_date FROM TX_SCHOOL_HOLIDAYS WHERE id = $id AND (is_deleted = 0 OR is_deleted IS NULL)";
$old_holiday_result = $conn->query($old_holiday_sql);

if ($old_holiday_result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Holiday not found']);
    $conn->close();
    exit;
}

$old_holiday = $old_holiday_result->fetch_assoc();
$old_start_date = $old_holiday['holiday_date'];
$old_end_date = $old_holiday['holiday_end_date'];
$school_id = $old_holiday['school_id'];

$new_start_date = $data['holiday_date'];
$new_end_date = !empty($data['holiday_end_date']) ? $data['holiday_end_date'] : null;

// Check if dates are being changed
$dates_changed = ($old_start_date != $new_start_date) || ($old_end_date != $new_end_date);

if ($dates_changed) {
    // Check for attendance records in the OLD date range
    $attendance_check = "";
    if ($old_end_date && $old_end_date != '0000-00-00' && $old_end_date != '') {
        $attendance_check = "SELECT COUNT(*) as count FROM TX_STUDENT_ATTENDANCE 
                             WHERE school_id = $school_id 
                             AND DATE(entry_dtm) BETWEEN '$old_start_date' AND '$old_end_date'";
    } else {
        $attendance_check = "SELECT COUNT(*) as count FROM TX_STUDENT_ATTENDANCE 
                             WHERE school_id = $school_id 
                             AND DATE(entry_dtm) = '$old_start_date'";
    }
    
    $attendance_result = $conn->query($attendance_check);
    $attendance_count = $attendance_result->fetch_assoc()['count'];
    
    if ($attendance_count > 0) {
        echo json_encode([
            'success' => false, 
            'message' => "Cannot modify holiday dates. $attendance_count attendance record(s) exist for this date. Please delete attendance records first or keep the original dates."
        ]);
        $conn->close();
        exit;
    }
}

// Validate academic year
$current_year = date('Y');
$academic_year = $conn->real_escape_string($data['academic_year']);
if (intval($academic_year) < $current_year) {
    echo json_encode(['success' => false, 'message' => 'Cannot update to a past academic year']);
    $conn->close();
    exit;
}

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