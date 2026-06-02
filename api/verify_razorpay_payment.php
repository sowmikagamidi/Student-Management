<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Razorpay Configuration
define('RAZORPAY_KEY_ID', 'rzp_test_SwJ0242iPOlpXS');
define('RAZORPAY_KEY_SECRET', 'ZW7L4Qsp1JovNh0Xh5dEuueT');

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
    $signature = isset($input['signature']) ? $input['signature'] : '';
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'online';
    $transaction_id = isset($input['transaction_id']) ? $input['transaction_id'] : null;
    
    if (!$order_id || !$payment_id || !$signature || !$student_id || !$term_name) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Verify signature
    $generatedSignature = hash_hmac('sha256', $order_id . '|' . $payment_id, RAZORPAY_KEY_SECRET);
    
    if ($generatedSignature !== $signature) {
        echo json_encode(['success' => false, 'message' => 'Invalid payment signature']);
        exit;
    }
    
    // Get order details to check payment method
    $orderSql = "SELECT payment_method FROM tx_payment_orders WHERE order_id = :order_id";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->execute([':order_id' => $order_id]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    $paymentMethod = $order ? $order['payment_method'] : $payment_method;
    
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
                      (:student_id, 'Term Fee', :term, :amount, :payment_method, :transaction_id, NOW(), NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':student_id' => $student_id,
            ':term' => $term_name,
            ':amount' => $amount,
            ':payment_method' => $paymentMethod,
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
        'message' => 'Payment verified and recorded successfully!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>