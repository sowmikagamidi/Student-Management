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
    
    // Log the received data for debugging
    error_log("Licence Add Request: " . print_r($input, true));
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $licence_type = isset($input['licence_type']) ? $input['licence_type'] : '';
    $school_id = isset($input['school_id']) ? (int)$input['school_id'] : 0;
    
    if (!$licence_type || !$school_id) {
        echo json_encode(['success' => false, 'message' => 'Licence type and school ID are required']);
        exit;
    }
    
    if ($licence_type === 'tv') {
        $class_id = isset($input['class_id']) ? (int)$input['class_id'] : 0;
        $used_status = isset($input['used_status']) ? $input['used_status'] : 'N';
        $joining_date = isset($input['joining_date']) ? $input['joining_date'] : date('Y-m-d');
        $expiry_date = isset($input['expiry_date']) ? $input['expiry_date'] : date('Y-m-d', strtotime('+1 year'));
        $tv_api_key = isset($input['tv_api_key']) ? $input['tv_api_key'] : null;
        
        if (!$class_id) {
            echo json_encode(['success' => false, 'message' => 'Class ID is required for TV licence']);
            exit;
        }
        
        $sql = "INSERT INTO tx_school_licence (licence_type, school_id, class_id, used_status, joining_date, expiry_date, tv_api_key, created_dtm) 
                VALUES (:licence_type, :school_id, :class_id, :used_status, :joining_date, :expiry_date, :tv_api_key, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':licence_type' => $licence_type,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':used_status' => $used_status,
            ':joining_date' => $joining_date,
            ':expiry_date' => $expiry_date,
            ':tv_api_key' => $tv_api_key
        ]);
        
        echo json_encode(['success' => true, 'message' => 'TV licence created successfully', 'licence_id' => $pdo->lastInsertId()]);
        
    } else if ($licence_type === 'lms') {
        $class_id = isset($input['class_id']) ? (int)$input['class_id'] : 0;
        $batch_id = isset($input['batch_id']) ? (int)$input['batch_id'] : null;
        $subscription_type = isset($input['subscription_type']) ? $input['subscription_type'] : 'D';
        $subscription_qty = isset($input['subscription_qty']) ? (int)$input['subscription_qty'] : 1;
        $available_qty = isset($input['available_qty']) ? (int)$input['available_qty'] : $subscription_qty - 1;
        $joining_date = isset($input['joining_date']) ? $input['joining_date'] : date('Y-m-d');
        $expiry_date = isset($input['expiry_date']) ? $input['expiry_date'] : date('Y-m-d', strtotime('+1 year'));
        
        $sql = "INSERT INTO tx_school_licence (licence_type, school_id, class_id, batch_id, subscription_type, subscription_qty, available_qty, joining_date, expiry_date, created_dtm) 
                VALUES (:licence_type, :school_id, :class_id, :batch_id, :subscription_type, :subscription_qty, :available_qty, :joining_date, :expiry_date, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':licence_type' => $licence_type,
            ':school_id' => $school_id,
            ':class_id' => $class_id,
            ':batch_id' => $batch_id,
            ':subscription_type' => $subscription_type,
            ':subscription_qty' => $subscription_qty,
            ':available_qty' => $available_qty,
            ':joining_date' => $joining_date,
            ':expiry_date' => $expiry_date
        ]);
        
        echo json_encode(['success' => true, 'message' => 'LMS licence created successfully', 'licence_id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid licence type']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>