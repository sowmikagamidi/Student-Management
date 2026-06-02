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
    
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'online';
    $transaction_id = isset($input['transaction_id']) ? $input['transaction_id'] : null;
    
    if (!$student_id || !$term_name || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get student details
    $studentSql = "SELECT full_name, email_id, mobile_number FROM users WHERE user_id = :student_id";
    $studentStmt = $pdo->prepare($studentSql);
    $studentStmt->execute([':student_id' => $student_id]);
    $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
        exit;
    }
    
    // Create Razorpay Order using cURL
    $amountInPaise = $amount * 100;
    
    $postData = json_encode([
        'amount' => $amountInPaise,
        'currency' => 'INR',
        'receipt' => 'receipt_' . time() . '_' . $student_id,
        'payment_capture' => 1,
        'notes' => [
            'student_id' => $student_id,
            'term_name' => $term_name,
            'payment_method' => $payment_method,
            'transaction_id' => $transaction_id
        ]
    ]);
    
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode(RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET)
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        echo json_encode(['success' => false, 'message' => 'cURL Error: ' . $curlError]);
        exit;
    }
    
    if ($httpCode != 200) {
        echo json_encode(['success' => false, 'message' => 'Razorpay Error: ' . $response]);
        exit;
    }
    
    $razorpayOrder = json_decode($response, true);
    
    if (!isset($razorpayOrder['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid response from Razorpay']);
        exit;
    }
    
    $order_id = $razorpayOrder['id'];
    
    // Store order in database
    $insertSql = "INSERT INTO tx_payment_orders (order_id, student_id, term_name, amount, payment_method, status, created_at) 
                   VALUES (:order_id, :student_id, :term_name, :amount, :payment_method, 'created', NOW())";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':order_id' => $order_id,
        ':student_id' => $student_id,
        ':term_name' => $term_name,
        ':amount' => $amount,
        ':payment_method' => $payment_method
    ]);
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'amount' => $amountInPaise,
        'currency' => 'INR',
        'student_name' => $student['full_name'],
        'student_email' => $student['email_id'] ?: 'student@example.com',
        'student_mobile' => $student['mobile_number'] ?: '9999999999'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>