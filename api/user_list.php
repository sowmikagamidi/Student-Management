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
    
    $school_id = isset($_GET['school_id']) ? (int)$_GET['school_id'] : 0;
    $role = isset($_GET['role']) ? $_GET['role'] : '';
    
    $sql = "SELECT u.user_id, u.full_name, u.user_name, u.email_id, u.mobile_number, u.country_code, 
                   u.gender, u.user_status, u.role, u.created_dtm, u.current_class,
                   u.batch_id, u.subscription_type, u.subject_id, u.school_id
            FROM users u 
            WHERE (u.is_deleted = 0 OR u.is_deleted IS NULL)";
    
    if ($school_id > 0) {
        $sql .= " AND u.school_id = :school_id";
    }
    if ($role) {
        $sql .= " AND u.role = :role";
    }
    $sql .= " ORDER BY u.user_id DESC";
    
    $stmt = $pdo->prepare($sql);
    if ($school_id > 0) {
        $stmt->execute([':school_id' => $school_id]);
    } else {
        $stmt->execute();
    }
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get additional info for each user
    foreach ($users as &$user) {
        // Get board from school if needed
        if ($user['school_id']) {
            $schoolSql = "SELECT board_id FROM tx_school_details WHERE school_id = :school_id";
            $schoolStmt = $pdo->prepare($schoolSql);
            $schoolStmt->execute([':school_id' => $user['school_id']]);
            $school = $schoolStmt->fetch(PDO::FETCH_ASSOC);
            $user['board_id'] = $school ? $school['board_id'] : null;
        }
        
        // Get batch/section info
        if ($user['batch_id']) {
            $batchSql = "SELECT section, academic_year, class_id FROM tx_class_batches WHERE batch_id = :batch_id";
            $batchStmt = $pdo->prepare($batchSql);
            $batchStmt->execute([':batch_id' => $user['batch_id']]);
            $batchInfo = $batchStmt->fetch(PDO::FETCH_ASSOC);
            if ($batchInfo) {
                $user['section'] = $batchInfo['section'];
                $user['academic_year'] = $batchInfo['academic_year'];
                $user['current_class'] = $user['current_class'] ?: $batchInfo['class_id'];
            }
        }
    }
    
    echo json_encode(['success' => true, 'data' => $users]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'data' => []]);
}
?>