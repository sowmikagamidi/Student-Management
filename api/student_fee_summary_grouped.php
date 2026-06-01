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
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    
    $school_id = isset($_GET['school_id']) ? (int)$_GET['school_id'] : 0;
    $academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';
    $class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    
    // First, get all students
    $studentSql = "SELECT user_id, full_name, mobile_number, current_class 
                   FROM users 
                   WHERE role = 'student' 
                   AND (is_deleted = 0 OR is_deleted IS NULL)";
    
    $params = [];
    
    if ($school_id > 0) {
        $studentSql .= " AND school_id = :school_id";
        $params[':school_id'] = $school_id;
    }
    if ($class_id > 0) {
        $studentSql .= " AND current_class = :class_id";
        $params[':class_id'] = $class_id;
    }
    if ($student_id > 0) {
        $studentSql .= " AND user_id = :student_id";
        $params[':student_id'] = $student_id;
    }
    
    $studentSql .= " ORDER BY user_id LIMIT :limit OFFSET :offset";
    
    $studentStmt = $pdo->prepare($studentSql);
    foreach ($params as $key => $value) {
        $studentStmt->bindValue($key, $value);
    }
    $studentStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $studentStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $studentStmt->execute();
    $students = $studentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total 
                 FROM users 
                 WHERE role = 'student' 
                 AND (is_deleted = 0 OR is_deleted IS NULL)";
    
    $countParams = [];
    if ($school_id > 0) {
        $countSql .= " AND school_id = :school_id";
        $countParams[':school_id'] = $school_id;
    }
    if ($class_id > 0) {
        $countSql .= " AND current_class = :class_id";
        $countParams[':class_id'] = $class_id;
    }
    if ($student_id > 0) {
        $countSql .= " AND user_id = :student_id";
        $countParams[':student_id'] = $student_id;
    }
    
    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;
    
    // For each student, get fee details using separate queries (avoid collation issues)
    $data = [];
    foreach ($students as $student) {
        $studentId = $student['user_id'];
        
        // Get total fee amount from tx_student_fee_details
        $feeSql = "SELECT COALESCE(SUM(amount), 0) as total, COUNT(DISTINCT term) as terms_total 
                   FROM tx_student_fee_details 
                   WHERE student_id = :student_id";
        
        if ($academic_year) {
            $feeSql .= " AND academic_year = :academic_year";
        }
        
        $feeStmt = $pdo->prepare($feeSql);
        $feeParams = [':student_id' => $studentId];
        if ($academic_year) {
            $feeParams[':academic_year'] = $academic_year;
        }
        $feeStmt->execute($feeParams);
        $feeData = $feeStmt->fetch(PDO::FETCH_ASSOC);
        
        $totalAmount = floatval($feeData['total']);
        $termsTotal = intval($feeData['terms_total']);
        
        // Get paid amount from tx_student_fee_payments
        $paidSql = "SELECT COALESCE(SUM(amount), 0) as paid 
                    FROM tx_student_fee_payments 
                    WHERE student_id = :student_id";
        $paidStmt = $pdo->prepare($paidSql);
        $paidStmt->execute([':student_id' => $studentId]);
        $paidAmount = floatval($paidStmt->fetch(PDO::FETCH_ASSOC)['paid']);
        
        // Get terms paid (where payment amount >= fee amount for that term)
        $termsPaidSql = "SELECT COUNT(DISTINCT fd.term) as terms_paid
                         FROM tx_student_fee_details fd
                         WHERE fd.student_id = :student_id
                         AND (
                             SELECT COALESCE(SUM(fp.amount), 0)
                             FROM tx_student_fee_payments fp
                             WHERE fp.student_id = fd.student_id
                             AND fp.term = fd.term
                         ) >= fd.amount";
        
        $termsPaidStmt = $pdo->prepare($termsPaidSql);
        $termsPaidStmt->execute([':student_id' => $studentId]);
        $termsPaid = intval($termsPaidStmt->fetch(PDO::FETCH_ASSOC)['terms_paid']);
        
        if ($totalAmount > 0) {
            $data[] = [
                'student_id' => $studentId,
                'student_name' => $student['full_name'],
                'mobile_number' => $student['mobile_number'],
                'class_id' => $student['current_class'],
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $totalAmount - $paidAmount,
                'terms_total' => $termsTotal > 0 ? $termsTotal : 1,
                'terms_paid' => $termsPaid
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'total_rows' => intval($totalRows),
        'total_pages' => intval($totalPages),
        'current_page' => $page,
        'limit' => $limit
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => []
    ]);
}
?>