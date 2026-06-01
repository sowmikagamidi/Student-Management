<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

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
    
    if (!$student_id || !$term_name) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Delete payments for this term first
    $deletePayments = "DELETE FROM tx_student_fee_payments WHERE student_id = :student_id AND term = :term";
    $pdo->prepare($deletePayments)->execute([':student_id' => $student_id, ':term' => $term_name]);
    
    // Delete fee details
    $deleteDetails = "DELETE FROM tx_student_fee_details WHERE student_id = :student_id AND term = :term";
    $pdo->prepare($deleteDetails)->execute([':student_id' => $student_id, ':term' => $term_name]);
    
    echo json_encode(['success' => true, 'message' => 'Term deleted successfully']);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>