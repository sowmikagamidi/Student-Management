<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 18;
    
    // Get all data from tx_student_fee_details for this student
    $sql = "SELECT * FROM tx_student_fee_details WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get distinct terms
    $termSql = "SELECT DISTINCT term FROM tx_student_fee_details WHERE student_id = :student_id";
    $termStmt = $pdo->prepare($termSql);
    $termStmt->execute([':student_id' => $student_id]);
    $terms = $termStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'student_id' => $student_id,
        'records_found' => count($data),
        'data' => $data,
        'distinct_terms' => $terms
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>