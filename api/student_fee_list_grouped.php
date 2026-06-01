<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(0);
ini_set('display_errors', 0);

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "school_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Set charset to avoid collation issues
    $conn->set_charset("utf8mb4");
    $conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;

    $school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;
    $academic_year = isset($_GET['academic_year']) ? $conn->real_escape_string($_GET['academic_year']) : '';
    $class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
    $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

    // Build where clause with proper casting
    $where = [];
    if ($school_id > 0) $where[] = "fd.school_id = " . intval($school_id);
    if ($academic_year) $where[] = "fd.academic_year = '" . $conn->real_escape_string($academic_year) . "'";
    if ($class_id > 0) $where[] = "u.class_id = " . intval($class_id);
    if ($student_id > 0) $where[] = "fd.student_id = " . intval($student_id);

    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "WHERE 1=1";

    // Get total count
    $countSql = "SELECT COUNT(DISTINCT fd.student_id) as total 
                 FROM tx_student_fee_details fd
                 INNER JOIN tx_users u ON fd.student_id = u.user_id
                 $whereClause";
    
    $countResult = $conn->query($countSql);
    $totalRows = 0;
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $totalRows = intval($row['total']);
    }
    $totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;

    // Get distinct students
    $studentSql = "SELECT DISTINCT fd.student_id, u.full_name as student_name, u.user_name, u.class_id, s.school_name
                   FROM tx_student_fee_details fd
                   INNER JOIN tx_users u ON fd.student_id = u.user_id
                   INNER JOIN tx_schools s ON fd.school_id = s.school_id
                   $whereClause
                   LIMIT $limit OFFSET $offset";

    $studentResult = $conn->query($studentSql);
    $data = [];

    if ($studentResult && $studentResult->num_rows > 0) {
        while ($studentRow = $studentResult->fetch_assoc()) {
            $studentIdVal = $studentRow['student_id'];
            
            // Get fees for this student
            $feeSql = "SELECT fd.*, 
                       COALESCE(ft.term_name, 'General') as term_name,
                       COALESCE(ft.term_amount, fd.amount) as term_amount,
                       COALESCE((SELECT SUM(p.amount) FROM tx_student_fee_payments p WHERE p.student_fee_id = fd.id), 0) as paid_amount
                       FROM tx_student_fee_details fd
                       LEFT JOIN tx_student_fee_terms ft ON fd.term_id = ft.id
                       WHERE fd.student_id = " . intval($studentIdVal);
            
            if ($school_id > 0) $feeSql .= " AND fd.school_id = " . intval($school_id);
            if ($academic_year) $feeSql .= " AND fd.academic_year = '" . $conn->real_escape_string($academic_year) . "'";
            
            $feeResult = $conn->query($feeSql);
            $fees = [];
            
            if ($feeResult && $feeResult->num_rows > 0) {
                while ($feeRow = $feeResult->fetch_assoc()) {
                    $fees[] = [
                        'id' => $feeRow['id'],
                        'fee_name' => $feeRow['fee_name'],
                        'amount' => floatval($feeRow['amount']),
                        'paid_amount' => floatval($feeRow['paid_amount']),
                        'group_id' => $feeRow['group_id'] ? intval($feeRow['group_id']) : 0,
                        'term_name' => $feeRow['term_name'],
                        'term_amount' => floatval($feeRow['term_amount']),
                        'due_date' => $feeRow['due_date'],
                        'created_date' => $feeRow['created_at']
                    ];
                }
            }
            
            $data[] = [
                'student' => [
                    'user_id' => $studentRow['student_id'],
                    'student_name' => $studentRow['student_name'],
                    'username' => $studentRow['user_name'],
                    'class_id' => $studentRow['class_id'],
                    'school_name' => $studentRow['school_name']
                ],
                'fees' => $fees
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $data,
        'total_rows' => $totalRows,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'data' => []
    ]);
}

if (isset($conn)) $conn->close();
?>