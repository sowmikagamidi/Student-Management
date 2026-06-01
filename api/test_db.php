<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $results = [];
    
    // Check users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
    $results['students_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Check fee details
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tx_student_fee_details");
    $results['fee_details_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Check payments
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tx_student_fee_payments");
    $results['payments_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Sample student fees
    $stmt = $pdo->query("SELECT fd.student_id, u.full_name, COUNT(*) as fee_count 
                         FROM tx_student_fee_details fd 
                         JOIN users u ON fd.student_id = u.user_id 
                         GROUP BY fd.student_id LIMIT 5");
    $results['sample_fees'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $results]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>