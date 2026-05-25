<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit;
    }
    
    // Soft delete by setting is_active = 0
    $stmt = $pdo->prepare("UPDATE tx_master_school_fee SET is_active = 0, updated_datetime = NOW() WHERE id = :id");
    $stmt->execute([':id' => $input['id']]);
    
    echo json_encode(['success' => true, 'message' => 'Fee deleted successfully']);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>