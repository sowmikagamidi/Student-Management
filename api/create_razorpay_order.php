<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'razorpay_config.php';

// Include Razorpay PHP SDK (Download from https://github.com/razorpay/razorpay-php)
// For now, we'll use cURL directly

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
    $currency = 'INR';
    
    $orderData = [
        'amount' => $amountInPaise,
        'currency' => $currency,
        'receipt' => 'receipt_' . time(),
        'payment_capture' => 1
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode(RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET)
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode != 200) {
        echo json_encode(['success' => false, 'message' => 'Failed to create Razorpay order: ' . $response]);
        exit;
    }
    
    $razorpayOrder = json_decode($response, true);
    $order_id = $razorpayOrder['id'];
    
    // Store order in database
    $insertSql = "INSERT INTO tx_payment_orders (order_id, student_id, term_name, amount, status, created_at) 
                   VALUES (:order_id, :student_id, :term_name, :amount, 'created', NOW())";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':order_id' => $order_id,
        ':student_id' => $student_id,
        ':term_name' => $term_name,
        ':amount' => $amount
    ]);
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'amount' => $amountInPaise,
        'currency' => $currency,
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