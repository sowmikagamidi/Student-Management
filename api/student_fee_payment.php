<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $fee_detail_id = isset($input['fee_detail_id']) ? $input['fee_detail_id'] : 0;
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'cash';
    $transaction_id = isset($input['transaction_id']) ? $input['transaction_id'] : null;
    
    if (!$fee_detail_id || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get fee details
    $feeSql = "SELECT * FROM tx_student_fee_details WHERE id = :id";
    $feeStmt = $pdo->prepare($feeSql);
    $feeStmt->execute([':id' => $fee_detail_id]);
    $fee = $feeStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$fee) {
        echo json_encode(['success' => false, 'message' => 'Fee record not found']);
        exit;
    }
    
    // Get current paid amount
    $paidSql = "SELECT COALESCE(SUM(amount), 0) as total_paid 
                FROM tx_student_fee_payments 
                WHERE student_id = :student_id AND fee_name = :fee_name AND term = :term";
    $paidStmt = $pdo->prepare($paidSql);
    $paidStmt->execute([
        ':student_id' => $fee['student_id'],
        ':fee_name' => $fee['fee_name'],
        ':term' => $fee['term']
    ]);
    $currentPaid = $paidStmt->fetch(PDO::FETCH_ASSOC)['total_paid'];
    
    $totalAfterPayment = $currentPaid + $amount;
    if ($totalAfterPayment > $fee['amount']) {
        echo json_encode([
            'success' => false,
            'message' => "Payment amount exceeds remaining balance of ₹" . number_format($fee['amount'] - $currentPaid, 2)
        ]);
        exit;
    }
    
    // Insert payment record
    $insertSql = "INSERT INTO tx_student_fee_payments 
                  (student_id, fee_name, term, amount, payment_method, transaction_id, payment_date, created_at) 
                  VALUES 
                  (:student_id, :fee_name, :term, :amount, :payment_method, :transaction_id, NOW(), NOW())";
    
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':student_id' => $fee['student_id'],
        ':fee_name' => $fee['fee_name'],
        ':term' => $fee['term'],
        ':amount' => $amount,
        ':payment_method' => $payment_method,
        ':transaction_id' => $transaction_id
    ]);
    
    // Update payment status in fee details
    if ($totalAfterPayment >= $fee['amount']) {
        $updateSql = "UPDATE tx_student_fee_details SET payment_status = 'S' WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([':id' => $fee_detail_id]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment recorded successfully',
        'payment_id' => $pdo->lastInsertId(),
        'amount_paid' => $totalAfterPayment,
        'remaining' => $fee['amount'] - $totalAfterPayment
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>