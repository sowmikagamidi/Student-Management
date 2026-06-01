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
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $term_name = isset($input['term_name']) ? $input['term_name'] : '';
    $due_date = isset($input['due_date']) ? $input['due_date'] : null;
    $fees = isset($input['fees']) ? $input['fees'] : [];
    
    if (!$student_id || !$term_name || empty($fees)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get student details
    $studentSql = "SELECT school_id, current_class FROM users WHERE user_id = :student_id";
    $studentStmt = $pdo->prepare($studentSql);
    $studentStmt->execute([':student_id' => $student_id]);
    $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
        exit;
    }
    
    $school_id = $student['school_id'];
    $class_id = $student['current_class'];
    $academic_year = date('Y');
    
    $inserted_count = 0;
    
    foreach ($fees as $fee) {
        $fee_name = $fee['fee_name'];
        $amount = floatval($fee['amount']);
        $group_id = isset($fee['group_id']) ? $fee['group_id'] : 0;
        
        $insertSql = "INSERT INTO tx_student_fee_details 
                     (student_id, school_id, class_id, group_id, fee_name, amount, academic_year, term, payment_status, due_date, created_datetime) 
                     VALUES 
                     (:student_id, :school_id, :class_id, :group_id, :fee_name, :amount, :academic_year, :term, 'P', :due_date, NOW())";
        
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':student_id' => $student_id,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':group_id' => $group_id,
            ':fee_name' => $fee_name,
            ':amount' => $amount,
            ':academic_year' => $academic_year,
            ':term' => $term_name,
            ':due_date' => $due_date
        ]);
        $inserted_count++;
    }
    
    echo json_encode(['success' => true, 'message' => "Term '$term_name' created with $inserted_count fee(s)"]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>