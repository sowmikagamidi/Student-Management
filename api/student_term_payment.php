<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

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
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'cash';
    $transaction_id = isset($input['transaction_id']) ? $input['transaction_id'] : null;
    $payment_date = date('Y-m-d');
    
    if (!$student_id || !$term_name || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get all fees for this student and term with their current paid amounts
    $feeSql = "SELECT fd.id, fd.fee_name, fd.amount, 
               COALESCE((
                   SELECT SUM(fp.amount) 
                   FROM tx_student_fee_payments fp 
                   WHERE fp.student_id = fd.student_id 
                   AND fp.term = fd.term 
                   AND fp.fee_name = fd.fee_name
               ), 0) as paid_so_far
               FROM tx_student_fee_details fd
               WHERE fd.student_id = :student_id AND fd.term = :term_name";
    
    $feeStmt = $pdo->prepare($feeSql);
    $feeStmt->execute([':student_id' => $student_id, ':term_name' => $term_name]);
    $fees = $feeStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($fees)) {
        echo json_encode(['success' => false, 'message' => 'No fees found for this term']);
        exit;
    }
    
    // Calculate total term amount and total paid
    $term_total = 0;
    $total_paid_so_far = 0;
    foreach ($fees as $fee) {
        $term_total += $fee['amount'];
        $total_paid_so_far += $fee['paid_so_far'];
    }
    
    $remaining = $term_total - $total_paid_so_far;
    
    if ($amount > $remaining) {
        echo json_encode([
            'success' => false,
            'message' => "Payment amount exceeds remaining balance of ₹" . number_format($remaining, 2)
        ]);
        exit;
    }
    
    // Distribute payment across fees
    $remaining_amount = $amount;
    $payment_success = true;
    
    foreach ($fees as $fee) {
        if ($remaining_amount <= 0) break;
        
        $fee_remaining = $fee['amount'] - $fee['paid_so_far'];
        if ($fee_remaining <= 0) continue;
        
        $pay_amount = min($remaining_amount, $fee_remaining);
        
        $insertSql = "INSERT INTO tx_student_fee_payments 
                      (student_id, fee_name, term, amount, payment_method, transaction_id, payment_date, created_at) 
                      VALUES 
                      (:student_id, :fee_name, :term, :amount, :payment_method, :transaction_id, :payment_date, NOW())";
        
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':student_id' => $student_id,
            ':fee_name' => $fee['fee_name'],
            ':term' => $term_name,
            ':amount' => $pay_amount,
            ':payment_method' => $payment_method,
            ':transaction_id' => $transaction_id,
            ':payment_date' => $payment_date
        ]);
        
        $remaining_amount -= $pay_amount;
    }
    
    if ($payment_success) {
        echo json_encode([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'amount_paid' => $total_paid_so_far + $amount,
            'term_total' => $term_total,
            'remaining' => $term_total - ($total_paid_so_far + $amount)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to record payment']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>