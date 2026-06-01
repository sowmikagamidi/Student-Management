<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $updates = isset($input['updates']) ? $input['updates'] : [];
    $new_term = isset($input['new_term']) ? $input['new_term'] : null;
    
    if (!$student_id) {
        echo json_encode(['success' => false, 'message' => 'Student ID required']);
        exit;
    }
    
    $updated_count = 0;
    
    // Process existing term updates (due dates)
    foreach ($updates as $update) {
        if ($update['type'] === 'due_date') {
            $sql = "UPDATE tx_student_fee_details SET due_date = :due_date WHERE student_id = :student_id AND term = :term";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':due_date' => $update['due_date'],
                ':student_id' => $student_id,
                ':term' => $update['term_name']
            ]);
            $updated_count += $stmt->rowCount();
        } elseif ($update['type'] === 'amount') {
            $sql = "UPDATE tx_student_fee_details SET amount = :amount WHERE id = :fee_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':amount' => $update['amount'],
                ':fee_id' => $update['fee_id']
            ]);
            $updated_count += $stmt->rowCount();
        }
    }
    
    // Process new term addition - FIXED!
    $new_term_created = false;
    if ($new_term && isset($new_term['fees']) && count($new_term['fees']) > 0) {
        $term_name = $new_term['term_name'];
        $due_date = isset($new_term['due_date']) ? $new_term['due_date'] : date('Y-m-d', strtotime('+30 days'));
        $fees = $new_term['fees'];
        
        // Get student details
        $studentSql = "SELECT school_id, current_class FROM users WHERE user_id = :student_id";
        $studentStmt = $pdo->prepare($studentSql);
        $studentStmt->execute([':student_id' => $student_id]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            $school_id = $student['school_id'];
            $class_id = $student['current_class'];
            $academic_year = date('Y');
            
            // First, check if this term already exists for this student
            $checkSql = "SELECT COUNT(*) as count FROM tx_student_fee_details WHERE student_id = :student_id AND term = :term";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                ':student_id' => $student_id,
                ':term' => $term_name
            ]);
            $existingCount = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            if ($existingCount > 0) {
                echo json_encode(['success' => false, 'message' => "Term '{$term_name}' already exists for this student"]);
                exit;
            }
            
            foreach ($fees as $fee) {
                $fee_name = $fee['fee_name'];
                $amount = floatval($fee['amount']);
                $group_id = isset($fee['group_id']) ? intval($fee['group_id']) : 0;
                
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
                $new_term_created = true;
            }
        }
    }
    
    $message = "";
    if ($updated_count > 0) {
        $message .= "$updated_count updates applied. ";
    }
    if ($new_term_created) {
        $message .= "New term '{$new_term['term_name']}' created successfully.";
    }
    if ($message == "") {
        $message = "No changes were made.";
    }
    
    echo json_encode(['success' => true, 'message' => $message]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>