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
    
    $tvLicences = [];
    $lmsLicences = [];
    
    // Get TV Licences
    if ($school_id > 0) {
        $tvSql = "SELECT licence_id, school_id, class_id, used_status, joining_date, expiry_date, 
                         tv_api_key, is_deleted, created_dtm 
                  FROM tx_school_licence 
                  WHERE school_id = :school_id AND licence_type = 'tv' 
                  ORDER BY licence_id DESC";
        $tvStmt = $pdo->prepare($tvSql);
        $tvStmt->execute([':school_id' => $school_id]);
        $tvLicences = $tvStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get LMS Licences with batch/section info
        $lmsSql = "SELECT l.*, cb.section, cb.academic_year as batch_academic_year,
                          (SELECT COUNT(*) FROM users WHERE batch_id = l.batch_id AND role = 'student' AND (is_deleted = 0 OR is_deleted IS NULL)) as used_count
                   FROM tx_school_licence l
                   LEFT JOIN tx_class_batches cb ON l.batch_id = cb.batch_id
                   WHERE l.school_id = :school_id AND l.licence_type = 'lms'
                   ORDER BY l.licence_id DESC";
        $lmsStmt = $pdo->prepare($lmsSql);
        $lmsStmt->execute([':school_id' => $school_id]);
        $lmsLicences = $lmsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate remaining slots for each licence
        foreach ($lmsLicences as &$licence) {
            $licence['used_count'] = isset($licence['used_count']) ? $licence['used_count'] : 0;
            $licence['remaining_slots'] = $licence['available_qty'];
        }
    }
    
    echo json_encode([
        'success' => true, 
        'data' => [
            'tv' => $tvLicences,
            'lms' => $lmsLicences
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>