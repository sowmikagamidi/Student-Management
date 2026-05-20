<?php
error_reporting(0);
header('Content-Type: application/json');

require_once 'config.php';

$response = ['success' => false, 'message' => 'User not found', 'data' => null];

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo json_encode($response);
    exit;
}

$userId = intval($_GET['user_id']);

$conn = getDbConnection();

$query = "SELECT u.*, 
          b.display_name as batch_name,
          b.section as batch_section
          FROM users u 
          LEFT JOIN batches b ON u.batch_id = b.batch_id 
          WHERE u.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $response = ['success' => true, 'message' => 'User found', 'data' => $row];
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>