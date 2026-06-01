<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    
    // Debug log
    error_log("student_id: " . $student_id);
    error_log("term: " . $term);
    
    if ($student_id == 0 || $term == '') {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing required fields: student_id and term are required',
            'received' => ['student_id' => $student_id, 'term' => $term]
        ]);
        exit;
    }
    
    // First, get all fees for this student and term
    $sql = "SELECT fd.*, 
            COALESCE(fd.amount, 0) as amount
            FROM tx_student_fee_details fd 
            WHERE fd.student_id = :student_id 
            AND fd.term = :term
            ORDER BY fd.group_id ASC, fd.id ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':student_id' => $student_id,
        ':term' => $term
    ]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get paid amounts for each fee
    foreach ($data as &$row) {
        // Check if payments table exists and get paid amount
        try {
            $paidSql = "SELECT COALESCE(SUM(amount), 0) as total_paid 
                        FROM tx_student_fee_payments 
                        WHERE student_id = :student_id 
                        AND fee_name = :fee_name 
                        AND term = :term";
            $paidStmt = $pdo->prepare($paidSql);
            $paidStmt->execute([
                ':student_id' => $row['student_id'],
                ':fee_name' => $row['fee_name'],
                ':term' => $row['term']
            ]);
            $paidResult = $paidStmt->fetch(PDO::FETCH_ASSOC);
            $row['paid_amount'] = $paidResult ? floatval($paidResult['total_paid']) : 0;
        } catch (Exception $e) {
            $row['paid_amount'] = 0;
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'count' => count($data),
        'student_id' => $student_id,
        'term' => $term
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}
?>