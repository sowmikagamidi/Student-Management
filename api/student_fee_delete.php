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
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    
    if (!$student_id) {
        echo json_encode(['success' => false, 'message' => 'Student ID required']);
        exit;
    }
    
    // Delete payments first
    $deletePayments = "DELETE FROM tx_student_fee_payments WHERE student_id = :student_id";
    $pdo->prepare($deletePayments)->execute([':student_id' => $student_id]);
    
    // Delete fee details
    $deleteDetails = "DELETE FROM tx_student_fee_details WHERE student_id = :student_id";
    $pdo->prepare($deleteDetails)->execute([':student_id' => $student_id]);
    
    echo json_encode(['success' => true, 'message' => 'Fee records deleted successfully']);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>