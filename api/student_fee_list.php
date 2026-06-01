<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

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
    
    $school_id = isset($_GET['school_id']) ? $_GET['school_id'] : '';
    $academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';
    $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
    
    // Build where clause
    $where = [];
    $params = [];
    
    if ($school_id && $school_id != '') {
        $where[] = "fd.school_id = :school_id";
        $params[':school_id'] = $school_id;
    }
    if ($academic_year && $academic_year != '') {
        $where[] = "fd.academic_year = :academic_year";
        $params[':academic_year'] = $academic_year;
    }
    if ($class_id && $class_id != '') {
        $where[] = "fd.class_id = :class_id";
        $params[':class_id'] = $class_id;
    }
    if ($student_id && $student_id != '') {
        $where[] = "fd.student_id = :student_id";
        $params[':student_id'] = $student_id;
    }
    
    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total 
                 FROM tx_student_fee_details fd 
                 $whereClause";
    
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $limit);
    
    // First get fee details
    $sql = "SELECT fd.* 
            FROM tx_student_fee_details fd 
            $whereClause 
            ORDER BY fd.id DESC 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Then get related data separately
    foreach ($data as &$row) {
        // Get student name
        if ($row['student_id']) {
            $studentStmt = $pdo->prepare("SELECT full_name, user_name FROM users WHERE user_id = :id");
            $studentStmt->execute([':id' => $row['student_id']]);
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            if ($student) {
                $row['student_name'] = $student['full_name'];
                $row['user_name'] = $student['user_name'];
            } else {
                $row['student_name'] = 'Unknown';
                $row['user_name'] = '';
            }
        } else {
            $row['student_name'] = 'Unknown';
            $row['user_name'] = '';
        }
        
        // Get school name
        if ($row['school_id']) {
            $schoolStmt = $pdo->prepare("SELECT school_name FROM tx_school_details WHERE school_id = :id");
            $schoolStmt->execute([':id' => $row['school_id']]);
            $school = $schoolStmt->fetch(PDO::FETCH_ASSOC);
            $row['school_name'] = $school ? $school['school_name'] : 'Unknown';
        } else {
            $row['school_name'] = 'Unknown';
        }
        
        // Get paid amount
        $paidStmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) as total_paid 
                                   FROM tx_student_fee_payments 
                                   WHERE student_id = :student_id 
                                   AND fee_name = :fee_name 
                                   AND term = :term");
        $paidStmt->execute([
            ':student_id' => $row['student_id'],
            ':fee_name' => $row['fee_name'],
            ':term' => $row['term']
        ]);
        $paidResult = $paidStmt->fetch(PDO::FETCH_ASSOC);
        $row['paid_amount'] = $paidResult ? $paidResult['total_paid'] : 0;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'total_rows' => (int)$totalRows,
        'total_pages' => (int)$totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>