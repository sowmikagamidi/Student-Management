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
    
    $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        exit;
    }
    
    $sql = "SELECT u.user_id, u.full_name, u.user_name, u.email_id, u.mobile_number, u.country_code, 
                   u.gender, u.user_status, u.role, u.created_dtm, u.current_class,
                   u.batch_id, u.subscription_type, u.subject_id, u.school_id
            FROM users u 
            WHERE u.user_id = :user_id AND (u.is_deleted = 0 OR u.is_deleted IS NULL)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    // Get school board
    if ($user['school_id']) {
        $schoolSql = "SELECT board_id, school_name FROM tx_school_details WHERE school_id = :school_id";
        $schoolStmt = $pdo->prepare($schoolSql);
        $schoolStmt->execute([':school_id' => $user['school_id']]);
        $school = $schoolStmt->fetch(PDO::FETCH_ASSOC);
        if ($school) {
            $user['board_id'] = $school['board_id'];
            $user['school_name'] = $school['school_name'];
        }
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
    
    echo json_encode(['success' => true, 'data' => $user]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>