<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$conn = getDBConnection();

$school_code = $_GET['code'] ?? '';

if (empty($school_code)) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $conn->prepare("SELECT school_id FROM TX_SCHOOL_DETAILS WHERE school_code = ?");
$stmt->bind_param('s', $school_code);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['exists' => $result->num_rows > 0]);

$stmt->close();
$conn->close();
?>