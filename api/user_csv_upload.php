<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $school_id = isset($_POST['school_id']) ? (int)$_POST['school_id'] : 0;
    
    if (!$school_id) {
        echo json_encode(['success' => false, 'message' => 'School ID is required']);
        exit;
    }
    
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Please upload a valid CSV file']);
        exit;
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Get available LMS licence
    $licenceSql = "SELECT licence_id, available_qty, subscription_qty, class_id 
                  FROM tx_school_licence 
                  WHERE school_id = :school_id 
                  AND licence_type = 'lms' 
                  AND expiry_date >= CURDATE() 
                  AND is_deleted = 0 
                  AND available_qty > 0
                  ORDER BY licence_id ASC 
                  LIMIT 1";
    
    $licenceStmt = $pdo->prepare($licenceSql);
    $licenceStmt->execute([':school_id' => $school_id]);
    $licence = $licenceStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$licence) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'No active LMS licence with available slots found']);
        exit;
    }
    
    $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
    $headers = fgetcsv($csvFile);
    
    $success_count = 0;
    $failed_count = 0;
    $errors = [];
    $usersToCreate = [];
    
    while (($row = fgetcsv($csvFile)) !== false) {
        $userData = [];
        foreach ($headers as $index => $header) {
            $userData[$header] = isset($row[$index]) ? trim($row[$index]) : '';
        }
        
        if (!empty($userData['full_name']) && !empty($userData['email'])) {
            $usersToCreate[] = $userData;
        }
    }
    fclose($csvFile);
    
    // Check if enough slots are available
    if (count($usersToCreate) > $licence['available_qty']) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false, 
            'message' => 'Not enough available slots. Available: ' . $licence['available_qty'] . ', Required: ' . count($usersToCreate)
        ]);
        exit;
    }
    
    foreach ($usersToCreate as $userData) {
        try {
            $full_name = $userData['full_name'];
            $email = $userData['email'];
            $mobile = isset($userData['mobile']) ? $userData['mobile'] : '';
            $class_id = isset($userData['class_id']) ? $userData['class_id'] : null;
            $subscription_type = isset($userData['subscription_type']) ? $userData['subscription_type'] : 'D';
            
            // Generate username
            $user_name = strtolower(explode('@', $email)[0]);
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_name = :user_name";
            $checkUserStmt = $pdo->prepare($checkUserSql);
            $checkUserStmt->execute([':user_name' => $user_name]);
            if ($checkUserStmt->fetchColumn() > 0) {
                $user_name = $user_name . rand(100, 999);
            }
            
            $sql = "INSERT INTO users (school_id, full_name, user_name, email_id, mobile_number, role, current_class, subscription_type, created_dtm) 
                    VALUES (:school_id, :full_name, :user_name, :email_id, :mobile_number, 'student', :current_class, :subscription_type, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':school_id' => $school_id,
                ':full_name' => $full_name,
                ':user_name' => $user_name,
                ':email_id' => $email,
                ':mobile_number' => $mobile,
                ':current_class' => $class_id,
                ':subscription_type' => $subscription_type
            ]);
            
            $success_count++;
            
        } catch(Exception $e) {
            $failed_count++;
            $errors[] = $full_name . ': ' . $e->getMessage();
        }
    }
    
    // Update available quantity
    if ($success_count > 0) {
        $newAvailableQty = $licence['available_qty'] - $success_count;
        $updateLicenceSql = "UPDATE tx_school_licence 
                            SET available_qty = :available_qty 
                            WHERE licence_id = :licence_id";
        $updateLicenceStmt = $pdo->prepare($updateLicenceSql);
        $updateLicenceStmt->execute([
            ':available_qty' => $newAvailableQty,
            ':licence_id' => $licence['licence_id']
        ]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'success_count' => $success_count,
        'failed_count' => $failed_count,
        'errors' => $errors,
        'remaining_slots' => $licence['available_qty'] - $success_count,
        'message' => $success_count . ' users created successfully. Remaining slots: ' . ($licence['available_qty'] - $success_count)
    ]);
    
} catch(PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>