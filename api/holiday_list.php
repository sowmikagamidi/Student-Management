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
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Build query with filters
$where = "WHERE (h.is_deleted = 0 OR h.is_deleted IS NULL)";
$params = [];
$types = "";

// School ID filter
if (isset($_GET['school_id']) && !empty($_GET['school_id']) && $_GET['school_id'] != '') {
    $where .= " AND h.school_id = ?";
    $params[] = intval($_GET['school_id']);
    $types .= "i";
}

// Academic Year filter
if (isset($_GET['academic_year']) && !empty($_GET['academic_year']) && $_GET['academic_year'] != '') {
    $where .= " AND h.academic_year = ?";
    $params[] = $_GET['academic_year'];
    $types .= "s";
}

// Board ID filter
if (isset($_GET['board_id']) && !empty($_GET['board_id']) && $_GET['board_id'] != '') {
    $where .= " AND h.board_id = ?";
    $params[] = $_GET['board_id'];
    $types .= "s";
}

// Class ID filter (show holidays for specific class OR all classes)
if (isset($_GET['class_id']) && !empty($_GET['class_id']) && $_GET['class_id'] != '') {
    $where .= " AND (h.class_id = ? OR h.class_id IS NULL)";
    $params[] = intval($_GET['class_id']);
    $types .= "i";
}

$sql = "SELECT h.*, s.school_name, s.school_code 
        FROM TX_SCHOOL_HOLIDAYS h 
        LEFT JOIN TX_SCHOOL_DETAILS s ON h.school_id = s.school_id 
        $where 
        ORDER BY h.holiday_date DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error, 'sql' => $sql]);
    $conn->close();
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$holidays = [];
while ($row = $result->fetch_assoc()) {
    $holidays[] = $row;
}

echo json_encode(['success' => true, 'data' => $holidays, 'count' => count($holidays)]);

$stmt->close();
$conn->close();
?>