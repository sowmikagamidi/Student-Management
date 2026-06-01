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
    error_log("Licence Update Request: " . print_r($input, true));
    
    // Check for licence_id
    $licence_id = isset($input['licence_id']) ? (int)$input['licence_id'] : 0;
    
    if (!$licence_id) {
        echo json_encode(['success' => false, 'message' => 'Licence ID is required']);
        exit;
    }
    
    // First, check what type of licence this is
    $typeSql = "SELECT licence_type FROM tx_school_licence WHERE licence_id = :licence_id";
    $typeStmt = $pdo->prepare($typeSql);
    $typeStmt->execute([':licence_id' => $licence_id]);
    $licence = $typeStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$licence) {
        echo json_encode(['success' => false, 'message' => 'Licence not found']);
        exit;
    }
    
    if ($licence['licence_type'] === 'tv') {
        // Update TV licence
        $class_id = isset($input['class_id']) ? (int)$input['class_id'] : 0;
        $used_status = isset($input['used_status']) ? $input['used_status'] : 'N';
        $joining_date = isset($input['joining_date']) ? $input['joining_date'] : date('Y-m-d');
        $expiry_date = isset($input['expiry_date']) ? $input['expiry_date'] : date('Y-m-d', strtotime('+1 year'));
        
        if (!$class_id) {
            echo json_encode(['success' => false, 'message' => 'Class ID is required']);
            exit;
        }
        
        $sql = "UPDATE tx_school_licence SET 
                    class_id = :class_id,
                    used_status = :used_status,
                    joining_date = :joining_date,
                    expiry_date = :expiry_date
                WHERE licence_id = :licence_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':class_id' => $class_id,
            ':used_status' => $used_status,
            ':joining_date' => $joining_date,
            ':expiry_date' => $expiry_date,
            ':licence_id' => $licence_id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'TV Licence updated successfully']);
        
    } else if ($licence['licence_type'] === 'lms') {
        // Update LMS licence
        $class_id = isset($input['class_id']) ? (int)$input['class_id'] : 0;
        $batch_id = isset($input['batch_id']) ? (int)$input['batch_id'] : null;
        $subscription_type = isset($input['subscription_type']) ? $input['subscription_type'] : 'D';
        $subscription_qty = isset($input['subscription_qty']) ? (int)$input['subscription_qty'] : 1;
        $available_qty = isset($input['available_qty']) ? (int)$input['available_qty'] : 0;
        $joining_date = isset($input['joining_date']) ? $input['joining_date'] : date('Y-m-d');
        $expiry_date = isset($input['expiry_date']) ? $input['expiry_date'] : date('Y-m-d', strtotime('+1 year'));
        
        if (!$class_id) {
            echo json_encode(['success' => false, 'message' => 'Class ID is required']);
            exit;
        }
        
        $sql = "UPDATE tx_school_licence SET 
                    class_id = :class_id,
                    batch_id = :batch_id,
                    subscription_type = :subscription_type,
                    subscription_qty = :subscription_qty,
                    available_qty = :available_qty,
                    joining_date = :joining_date,
                    expiry_date = :expiry_date
                WHERE licence_id = :licence_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':class_id' => $class_id,
            ':batch_id' => $batch_id,
            ':subscription_type' => $subscription_type,
            ':subscription_qty' => $subscription_qty,
            ':available_qty' => $available_qty,
            ':joining_date' => $joining_date,
            ':expiry_date' => $expiry_date,
            ':licence_id' => $licence_id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'LMS Licence updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid licence type']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>