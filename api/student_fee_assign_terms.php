<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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
    
    $school_id = isset($input['school_id']) ? intval($input['school_id']) : 0;
    $academic_year = isset($input['academic_year']) ? $input['academic_year'] : '';
    $class_id = isset($input['class_id']) ? intval($input['class_id']) : 0;
    $terms = isset($input['terms']) ? $input['terms'] : [];
    
    if (!$school_id || !$academic_year || !$class_id || empty($terms)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Get all students in the selected school and class
    $studentSql = "SELECT u.user_id, u.full_name, u.user_name, u.current_class
                   FROM users u 
                   WHERE u.school_id = :school_id 
                   AND u.current_class = :class_id 
                   AND u.role = 'student' 
                   AND u.user_status = 'A'
                   AND (u.is_deleted = 0 OR u.is_deleted IS NULL)";
    
    $stmt = $pdo->prepare($studentSql);
    $stmt->execute([':school_id' => $school_id, ':class_id' => $class_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($students)) {
        echo json_encode(['success' => false, 'message' => 'No students found in the selected class']);
        exit;
    }
    
    $assigned_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    $errors = [];
    $skipped_students = [];
    
    foreach ($students as $student) {
        $student_id = $student['user_id'];
        $student_name = $student['full_name'];
        $has_any_fee = false;
        
        foreach ($terms as $termIndex => $term) {
            $term_name = $term['term_name'];
            $term_due_date = isset($term['due_date']) ? $term['due_date'] : date('Y-m-d', strtotime('+30 days'));
            $fees = isset($term['fees']) ? $term['fees'] : [];
            
            // Check if fees for this student, academic year, and term already exist
            $checkSql = "SELECT COUNT(*) as count FROM tx_student_fee_details 
                        WHERE student_id = :student_id 
                        AND academic_year = :academic_year 
                        AND term = :term";
            
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                ':student_id' => $student_id,
                ':academic_year' => $academic_year,
                ':term' => $term_name
            ]);
            $existingCount = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            if ($existingCount > 0) {
                $skipped_count++;
                $skipped_students[] = $student_name;
                continue;
            }
            
            foreach ($fees as $fee) {
                $fee_name = $fee['fee_name'];
                $amount = floatval($fee['amount']);
                $group_id = isset($fee['group_id']) ? $fee['group_id'] : 0;
                
                $insertFeeSql = "INSERT INTO tx_student_fee_details 
                                 (student_id, school_id, class_id, group_id, fee_name, amount, academic_year, term, payment_status, due_date, created_datetime) 
                                 VALUES 
                                 (:student_id, :school_id, :class_id, :group_id, :fee_name, :amount, :academic_year, :term, 'P', :due_date, NOW())";
                
                $feeStmt = $pdo->prepare($insertFeeSql);
                $feeStmt->execute([
                    ':student_id' => $student_id,
                    ':school_id' => $school_id,
                    ':class_id' => $class_id,
                    ':group_id' => $group_id,
                    ':fee_name' => $fee_name,
                    ':amount' => $amount,
                    ':academic_year' => $academic_year,
                    ':term' => $term_name,
                    ':due_date' => $term_due_date
                ]);
                $has_any_fee = true;
            }
        }
        
        if ($has_any_fee) {
            $assigned_count++;
        }
    }
    
    $message = "";
    if ($assigned_count > 0) {
        $message = "Fees assigned to $assigned_count student(s)";
    }
    if ($skipped_count > 0) {
        $unique_skipped = array_unique($skipped_students);
        $message .= ". Skipped " . count($unique_skipped) . " student(s) who already have fees assigned for this academic year/term.";
    }
    if ($failed_count > 0) {
        $message .= " Failed: $failed_count";
    }
    
    echo json_encode([
        'success' => true,
        'assigned_count' => $assigned_count,
        'failed_count' => $failed_count,
        'skipped_count' => $skipped_count,
        'skipped_students' => array_unique($skipped_students),
        'message' => $message
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>