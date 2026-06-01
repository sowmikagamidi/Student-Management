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
    $fee_id = isset($input['fee_id']) ? (int)$input['fee_id'] : 0;
    
    if (!$fee_id) {
        echo json_encode(['success' => false, 'message' => 'Fee ID required']);
        exit;
    }
    
    // Get student_id and fee details first
    $getSql = "SELECT student_id, fee_name, term FROM tx_student_fee_details WHERE id = :id";
    $getStmt = $pdo->prepare($getSql);
    $getStmt->execute([':id' => $fee_id]);
    $fee = $getStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($fee) {
        // Delete payments for this specific fee
        $deletePayments = "DELETE FROM tx_student_fee_payments WHERE student_id = :student_id AND fee_name = :fee_name AND term = :term";
        $pdo->prepare($deletePayments)->execute([
            ':student_id' => $fee['student_id'],
            ':fee_name' => $fee['fee_name'],
            ':term' => $fee['term']
        ]);
    }
    
    // Delete the fee detail
    $deleteDetails = "DELETE FROM tx_student_fee_details WHERE id = :id";
    $pdo->prepare($deleteDetails)->execute([':id' => $fee_id]);
    
    echo json_encode(['success' => true, 'message' => 'Fee deleted successfully']);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>