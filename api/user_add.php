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
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $school_id = isset($input['school_id']) ? (int)$input['school_id'] : 0;
    $full_name = isset($input['full_name']) ? trim($input['full_name']) : '';
    $email_id = isset($input['email_id']) ? trim($input['email_id']) : '';
    $mobile_number = isset($input['mobile_number']) ? trim($input['mobile_number']) : '';
    $country_code = isset($input['country_code']) ? $input['country_code'] : '+91';
    $role = isset($input['role']) ? $input['role'] : 'student';
    $user_status = isset($input['user_status']) ? $input['user_status'] : 'A';
    $gender = isset($input['gender']) ? $input['gender'] : null;
    $user_name = isset($input['user_name']) ? trim($input['user_name']) : null;
    $current_class = isset($input['current_class']) ? $input['current_class'] : null;
    $batch_id = isset($input['batch_id']) ? (int)$input['batch_id'] : null;
    $subscription_type = isset($input['subscription_type']) ? $input['subscription_type'] : 'D';
    $subject_id = isset($input['subject_id']) ? $input['subject_id'] : null;
    
    if (!$school_id || !$full_name || !$email_id) {
        echo json_encode(['success' => false, 'message' => 'School ID, Full Name, and Email are required']);
        exit;
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // If creating a student, check and update LMS licence available quantity
        if ($role === 'student') {
            // Find an active LMS licence with available quantity
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
                echo json_encode(['success' => false, 'message' => 'No active LMS licence with available slots found. Please add an LMS licence first.']);
                exit;
            }
            
            // Decrease available quantity by 1
            $newAvailableQty = $licence['available_qty'] - 1;
            $updateLicenceSql = "UPDATE tx_school_licence 
                                SET available_qty = :available_qty 
                                WHERE licence_id = :licence_id";
            $updateLicenceStmt = $pdo->prepare($updateLicenceSql);
            $updateLicenceStmt->execute([
                ':available_qty' => $newAvailableQty,
                ':licence_id' => $licence['licence_id']
            ]);
        }
        
        // Check if email already exists
        $checkSql = "SELECT COUNT(*) as count FROM users WHERE email_id = :email_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([':email_id' => $email_id]);
        $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($exists['count'] > 0) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        }
        
        // Generate username if not provided
        if (!$user_name) {
            $user_name = strtolower(explode('@', $email_id)[0]);
            // Check if username exists
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_name = :user_name";
            $checkUserStmt = $pdo->prepare($checkUserSql);
            $checkUserStmt->execute([':user_name' => $user_name]);
            $userExists = $checkUserStmt->fetch(PDO::FETCH_ASSOC);
            if ($userExists['count'] > 0) {
                $user_name = $user_name . rand(100, 999);
            }
        }
        
        $sql = "INSERT INTO users (school_id, full_name, user_name, email_id, mobile_number, country_code, 
                                   role, user_status, gender, current_class, batch_id, subscription_type, subject_id, created_dtm) 
                VALUES (:school_id, :full_name, :user_name, :email_id, :mobile_number, :country_code, 
                        :role, :user_status, :gender, :current_class, :batch_id, :subscription_type, :subject_id, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':school_id' => $school_id,
            ':full_name' => $full_name,
            ':user_name' => $user_name,
            ':email_id' => $email_id,
            ':mobile_number' => $mobile_number,
            ':country_code' => $country_code,
            ':role' => $role,
            ':user_status' => $user_status,
            ':gender' => $gender,
            ':current_class' => $current_class,
            ':batch_id' => $batch_id,
            ':subscription_type' => $subscription_type,
            ':subject_id' => $subject_id
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // Commit transaction
        $pdo->commit();
        
        $message = 'User created successfully';
        if ($role === 'student' && isset($licence)) {
            $message .= ' | Remaining available slots: ' . ($licence['available_qty'] - 1);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => $message,
            'user_id' => $userId,
            'user_name' => $user_name,
            'remaining_slots' => isset($licence) ? ($licence['available_qty'] - 1) : null
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>