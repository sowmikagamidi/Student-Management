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
if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Note: key field is NOT updated (immutable)
$sql = "UPDATE TX_SCHOOL_API_KEYS SET 
            class_id = ?,
            validity = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isi', $data['class_id'], $data['validity'], $data['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'API key updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>