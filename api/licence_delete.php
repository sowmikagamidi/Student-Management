<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Log the received data for debugging
    error_log("Licence Delete Request: " . print_r($input, true));
    
    // Check for licence_id in the request
    $licence_id = isset($input['licence_id']) ? (int)$input['licence_id'] : 0;
    
    if (!$licence_id) {
        echo json_encode(['success' => false, 'message' => 'Licence ID is required']);
        exit;
    }
    
    // Soft delete - update is_deleted flag
    $sql = "UPDATE tx_school_licence SET is_deleted = 1 WHERE licence_id = :licence_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':licence_id' => $licence_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Licence deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Licence not found or already deleted']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>