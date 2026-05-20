<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['batch_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Check if updating would create duplicate class+section combination (excluding current batch)
$checkSql = "SELECT batch_id FROM TX_CLASS_BATCHES 
             WHERE school_id = (SELECT school_id FROM TX_CLASS_BATCHES WHERE batch_id = ?) 
             AND class_id = (SELECT class_id FROM TX_CLASS_BATCHES WHERE batch_id = ?)
             AND section = ?
             AND batch_id != ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param('iisi', $data['batch_id'], $data['batch_id'], $data['section'], $data['batch_id']);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Class with Section "' . $data['section'] . '" already exists for this school!']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$sql = "UPDATE TX_CLASS_BATCHES SET 
            section = ?,
            board_id = ?,
            academic_year = ?,
            student_count = ?
        WHERE batch_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sssii', 
    $data['section'],
    $data['board_id'],
    $data['academic_year'],
    $data['student_count'],
    $data['batch_id']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Class updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>