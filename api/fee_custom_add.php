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
    
    if (empty($input['fee_name']) || empty($input['amount'])) {
        echo json_encode(['success' => false, 'message' => 'Fee name and amount are required']);
        exit;
    }
    
    // Check if already exists
    $check = $pdo->prepare("SELECT id FROM tx_master_school_fee WHERE fee_name = :name");
    $check->execute([':name' => $input['fee_name']]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Fee name already exists']);
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO tx_master_school_fee (fee_name, amount, is_active, created_datetime) VALUES (:name, :amount, 1, NOW())");
    $stmt->execute([
        ':name' => $input['fee_name'],
        ':amount' => $input['amount']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Fee added successfully', 'id' => $pdo->lastInsertId()]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>