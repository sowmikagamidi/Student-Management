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
    echo json_encode(['success' => false, 'message' => 'No batch ID received']);
    exit;
}

$sql = "DELETE FROM TX_CLASS_BATCHES WHERE batch_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $data['batch_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Class deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>