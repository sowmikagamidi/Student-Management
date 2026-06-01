<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $order_id = isset($input['order_id']) ? $input['order_id'] : '';
    $payment_id = isset($input['payment_id']) ? $input['payment_id'] : '';
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    
    if (!$order_id || !$payment_id || !$student_id || !$term_name) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Update order status
    $updateOrderSql = "UPDATE tx_payment_orders SET payment_id = :payment_id, status = 'completed', updated_at = NOW() 
                        WHERE order_id = :order_id";
    $updateOrderStmt = $pdo->prepare($updateOrderSql);
    $updateOrderStmt->execute([
        ':payment_id' => $payment_id,
        ':order_id' => $order_id
    ]);
    
    // Check if payment already exists
    $checkSql = "SELECT id FROM tx_student_fee_payments 
                 WHERE student_id = :student_id AND term = :term_name AND transaction_id = :payment_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([
        ':student_id' => $student_id,
        ':term_name' => $term_name,
        ':payment_id' => $payment_id
    ]);
    
    if (!$checkStmt->fetch()) {
        // Insert payment record
        $insertSql = "INSERT INTO tx_student_fee_payments 
                      (student_id, fee_name, term, amount, payment_method, transaction_id, payment_date, created_at) 
                      VALUES 
                      (:student_id, 'Term Fee', :term, :amount, 'razorpay', :transaction_id, NOW(), NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':student_id' => $student_id,
            ':term' => $term_name,
            ':amount' => $amount,
            ':transaction_id' => $payment_id
        ]);
        
        // Update payment status in fee details
        $updateFeeSql = "UPDATE tx_student_fee_details SET payment_status = 'S' 
                         WHERE student_id = :student_id AND term = :term";
        $updateFeeStmt = $pdo->prepare($updateFeeSql);
        $updateFeeStmt->execute([
            ':student_id' => $student_id,
            ':term' => $term_name
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment successful!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>