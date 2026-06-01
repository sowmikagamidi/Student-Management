<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    
    if (!$student_id) {
        echo json_encode(['success' => false, 'message' => 'Student ID required']);
        exit;
    }
    
    // Get all distinct terms for this student with their due dates
    $termSql = "SELECT DISTINCT term, due_date FROM tx_student_fee_details WHERE student_id = :student_id AND term IS NOT NULL AND term != '' ORDER BY term";
    $termStmt = $pdo->prepare($termSql);
    $termStmt->execute([':student_id' => $student_id]);
    $terms = $termStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($terms)) {
        echo json_encode(['success' => true, 'data' => []]);
        exit;
    }
    
    $result = [];
    
    foreach ($terms as $termData) {
        $termName = $termData['term'];
        $dueDate = $termData['due_date'];
        
        // Get all fees for this term with their group info and payment status
        $feeSql = "SELECT fd.id, fd.fee_name, fd.amount, fd.group_id, fd.due_date,
                   COALESCE((
                       SELECT SUM(fp.amount) 
                       FROM tx_student_fee_payments fp 
                       WHERE fp.student_id = fd.student_id 
                       AND fp.term = fd.term 
                       AND fp.fee_name = fd.fee_name
                   ), 0) as paid_amount
                   FROM tx_student_fee_details fd
                   WHERE fd.student_id = :student_id AND fd.term = :term
                   ORDER BY fd.group_id, fd.id";
        
        $feeStmt = $pdo->prepare($feeSql);
        $feeStmt->execute([':student_id' => $student_id, ':term' => $termName]);
        $fees = $feeStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_amount = 0;
        $paid_amount = 0;
        
        foreach ($fees as &$fee) {
            $fee['amount'] = floatval($fee['amount']);
            $fee['paid_amount'] = floatval($fee['paid_amount']);
            $total_amount += $fee['amount'];
            $paid_amount += $fee['paid_amount'];
            $fee['group_name'] = $fee['group_id'] ? 'Group ' . $fee['group_id'] : 'General';
        }
        
        $result[] = [
            'term_name' => $termName,
            'due_date' => $dueDate ? $dueDate : null,
            'total_amount' => $total_amount,
            'paid_amount' => $paid_amount,
            'fees' => $fees
        ];
    }
    
    // Sort terms by number (Term 1, Term 2, etc.)
    usort($result, function($a, $b) {
        preg_match('/(\d+)/', $a['term_name'], $aNum);
        preg_match('/(\d+)/', $b['term_name'], $bNum);
        $aVal = isset($aNum[1]) ? intval($aNum[1]) : 0;
        $bVal = isset($bNum[1]) ? intval($bNum[1]) : 0;
        return $aVal - $bVal;
    });
    
    echo json_encode(['success' => true, 'data' => $result]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'data' => []]);
}
?>