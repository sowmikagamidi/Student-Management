<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    $term_name = isset($_GET['term_name']) ? $_GET['term_name'] : '';
    
    if (!$student_id || !$term_name) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get total fee amount for the term
    $feeSql = "SELECT SUM(amount) as total_amount 
               FROM tx_student_fee_details 
               WHERE student_id = :student_id AND term = :term_name";
    $feeStmt = $pdo->prepare($feeSql);
    $feeStmt->execute([
        ':student_id' => $student_id,
        ':term_name' => $term_name
    ]);
    $feeResult = $feeStmt->fetch(PDO::FETCH_ASSOC);
    $totalAmount = floatval($feeResult['total_amount']);
    
    // Get paid amount for the term
    $paidSql = "SELECT SUM(amount) as paid_amount 
                FROM tx_student_fee_payments 
                WHERE student_id = :student_id AND term = :term_name";
    $paidStmt = $pdo->prepare($paidSql);
    $paidStmt->execute([
        ':student_id' => $student_id,
        ':term_name' => $term_name
    ]);
    $paidResult = $paidStmt->fetch(PDO::FETCH_ASSOC);
    $paidAmount = floatval($paidResult['paid_amount']);
    
    $is_paid = ($paidAmount >= $totalAmount && $totalAmount > 0);
    
    echo json_encode([
        'success' => true,
        'is_paid' => $is_paid,
        'total_amount' => $totalAmount,
        'paid_amount' => $paidAmount
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>