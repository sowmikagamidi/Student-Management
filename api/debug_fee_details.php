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
    
    // Check if there's any data in tx_student_fee_details
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tx_student_fee_details");
    $totalCount = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get sample data
    $sampleStmt = $pdo->query("SELECT * FROM tx_student_fee_details LIMIT 5");
    $sampleData = $sampleStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get distinct terms
    $termStmt = $pdo->query("SELECT DISTINCT term FROM tx_student_fee_details WHERE term IS NOT NULL");
    $terms = $termStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get distinct students
    $studentStmt = $pdo->query("SELECT DISTINCT student_id FROM tx_student_fee_details");
    $students = $studentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total_records' => $totalCount['count'],
        'sample_data' => $sampleData,
        'distinct_terms' => $terms,
        'distinct_students' => $students,
        'message' => 'Debug information'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>