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

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0) {
    echo json_encode(['success' => false, 'message' => 'Please upload a CSV file']);
    exit;
}

$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
if (!$file) {
    echo json_encode(['success' => false, 'message' => 'Unable to read file']);
    exit;
}

// Read headers
$headers = fgetcsv($file);
$expectedHeaders = ['school_id', 'academic_year', 'board_id', 'class_id', 'holiday_date', 'holiday_end_date', 'holiday_name', 'holiday_type'];

$success_count = 0;
$failed_count = 0;
$errors = [];
$current_year = date('Y');

while (($row = fgetcsv($file)) !== false) {
    $data = array_combine($headers, $row);
    
    // Validate required fields
    if (empty($data['school_id']) || empty($data['academic_year']) || empty($data['board_id']) || 
        empty($data['holiday_date']) || empty($data['holiday_name']) || empty($data['holiday_type'])) {
        $failed_count++;
        $errors[] = "Missing required fields in row";
        continue;
    }
    
    $school_id = intval($data['school_id']);
    $academic_year = $conn->real_escape_string($data['academic_year']);
    $board_id = $conn->real_escape_string($data['board_id']);
    $class_id = !empty($data['class_id']) ? intval($data['class_id']) : 'NULL';
    $holiday_date = $data['holiday_date'];
    $holiday_end_date = !empty($data['holiday_end_date']) ? "'" . $data['holiday_end_date'] . "'" : 'NULL';
    $holiday_name = $conn->real_escape_string($data['holiday_name']);
    $holiday_type = $data['holiday_type'];
    
    // Validate board
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
            $failed_count++;
            $errors[] = "Board mismatch for school ID $school_id (has $school_board, tried $board_id)";
            continue;
        }
    } else {
        $failed_count++;
        $errors[] = "School ID $school_id not found";
        continue;
    }
    
    // Validate academic year
    if (intval($academic_year) < $current_year) {
        $failed_count++;
        $errors[] = "Past academic year $academic_year for school $school_id";
        continue;
    }
    
    // Check duplicate
    $duplicate_check = "SELECT id FROM TX_SCHOOL_HOLIDAYS 
                        WHERE school_id = $school_id 
                        AND (is_deleted = 0 OR is_deleted IS NULL)
                        AND holiday_date = '$holiday_date'
                        AND holiday_name = '$holiday_name'";
    $dup_result = $conn->query($duplicate_check);
    if ($dup_result && $dup_result->num_rows > 0) {
        $failed_count++;
        $errors[] = "Duplicate holiday for school $school_id: $holiday_name on $holiday_date";
        continue;
    }
    
    $sql = "INSERT INTO TX_SCHOOL_HOLIDAYS (school_id, academic_year, class_id, board_id, holiday_date, holiday_end_date, holiday_name, holiday_type, created_dtm) 
            VALUES ($school_id, '$academic_year', " . ($class_id == 'NULL' ? 'NULL' : $class_id) . ", '$board_id', '$holiday_date', $holiday_end_date, '$holiday_name', '$holiday_type', NOW())";
    
    if ($conn->query($sql)) {
        $success_count++;
    } else {
        $failed_count++;
        $errors[] = "Failed to insert: " . $holiday_name . " - " . $conn->error;
    }
}

fclose($file);
$conn->close();

echo json_encode([
    'success' => true,
    'success_count' => $success_count,
    'failed_count' => $failed_count,
    'errors' => $errors,
    'message' => "$success_count holidays added, $failed_count failed"
]);
?>
<?php