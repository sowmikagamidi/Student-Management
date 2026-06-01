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
    
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'cash';
    $transaction_id = isset($input['transaction_id']) ? $input['transaction_id'] : null;
    
    if (!$student_id || !$term_name || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Check if term exists and get remaining balance
    $checkSql = "SELECT 
                    SUM(fd.amount) as total_amount,
                    COALESCE(SUM(fp.amount), 0) as paid_amount
                 FROM tx_student_fee_details fd
                 LEFT JOIN tx_student_fee_payments fp ON fd.student_id = fp.student_id AND fd.term = fp.term
                 WHERE fd.student_id = :student_id AND fd.term = :term_name
                 GROUP BY fd.term";
    
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([
        ':student_id' => $student_id,
        ':term_name' => $term_name
    ]);
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $total_amount = floatval($result['total_amount']);
        $paid_amount = floatval($result['paid_amount']);
        $remaining_amount = $total_amount - $paid_amount;
        
        if ($amount > $remaining_amount) {
            echo json_encode([
                'success' => false, 
                'message' => "Payment amount (₹" . number_format($amount, 2) . 
                            ") exceeds remaining balance (₹" . number_format($remaining_amount, 2) . ")"
            ]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No fee details found for this term']);
        exit;
    }
    
    // Generate transaction ID if not provided
    if (!$transaction_id) {
        $transaction_id = 'TXN_' . time() . '_' . $student_id . '_' . rand(1000, 9999);
    }
    
    // Insert payment record
    $insertSql = "INSERT INTO tx_student_fee_payments 
                  (student_id, fee_name, term, amount, payment_method, transaction_id, payment_date, created_at) 
                  VALUES 
                  (:student_id, 'Term Fee', :term, :amount, :payment_method, :transaction_id, NOW(), NOW())";
    
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':student_id' => $student_id,
        ':term' => $term_name,
        ':amount' => $amount,
        ':payment_method' => $payment_method,
        ':transaction_id' => $transaction_id
    ]);
    
    // Check if term is now fully paid
    $newCheckSql = "SELECT 
                       SUM(fd.amount) as total_amount,
                       COALESCE(SUM(fp.amount), 0) as paid_amount
                    FROM tx_student_fee_details fd
                    LEFT JOIN tx_student_fee_payments fp ON fd.student_id = fp.student_id AND fd.term = fp.term
                    WHERE fd.student_id = :student_id AND fd.term = :term_name";
    
    $newCheckStmt = $pdo->prepare($newCheckSql);
    $newCheckStmt->execute([
        ':student_id' => $student_id,
        ':term_name' => $term_name
    ]);
    $newResult = $newCheckStmt->fetch(PDO::FETCH_ASSOC);
    
    $new_total = floatval($newResult['total_amount']);
    $new_paid = floatval($newResult['paid_amount']);
    
    // If fully paid, update payment status in fee details
    if ($new_paid >= $new_total && $new_total > 0) {
        $updateSql = "UPDATE tx_student_fee_details SET payment_status = 'S' 
                      WHERE student_id = :student_id AND term = :term";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':student_id' => $student_id,
            ':term' => $term_name
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment recorded successfully',
        'transaction_id' => $transaction_id,
        'remaining_balance' => $new_total - $new_paid
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>