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
    
    if (!$school_id) {
        echo json_encode(['success' => false, 'message' => 'School ID required']);
        exit;
    }
    
    // Get expiry date from tx_student_enrollment table
    $sql = "SELECT expiry_date FROM tx_student_enrollment WHERE school_id = :school_id ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':school_id' => $school_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['expiry_date']) {
        echo json_encode(['success' => true, 'expiry_date' => $result['expiry_date']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No expiry date found']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>