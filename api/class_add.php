<?php
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
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

// Check for duplicate class with SAME class number AND SAME section
$checkSql = "SELECT batch_id FROM TX_CLASS_BATCHES WHERE school_id = ? AND class_id = ? AND section = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param('iis', $data['school_id'], $data['class_id'], $data['section']);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Class ' . $data['class_id'] . ' with Section "' . $data['section'] . '" already exists for this school!']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$sql = "INSERT INTO TX_CLASS_BATCHES (board_id, class_id, school_id, class_name, section, academic_year, student_count, created_dtm) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$student_count = $data['student_count'] ?? 100;

$stmt->bind_param('siisssi', 
    $data['board_id'], 
    $data['class_id'], 
    $data['school_id'], 
    $data['class_name'], 
    $data['section'], 
    $data['academic_year'],
    $student_count
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Class added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>