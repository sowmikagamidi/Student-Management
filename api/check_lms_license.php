<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = ['success' => false, 'message' => 'No active LMS license found'];

try {
    $school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;
    
    if (!$school_id) {
        throw new Exception('School ID is required');
    }

    $conn = new mysqli('localhost', 'root', '', 'tutorix_db');
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Check if LMS license exists and is active
    $query = "SELECT 
                licence_id, 
                available_qty, 
                subscription_type, 
                class_id, 
                board_id,
                expiry_date
            FROM TX_SCHOOL_LICENCE
            WHERE school_id = ? 
            AND licence_type = 'lms' 
            AND expiry_date >= CURDATE() 
            AND is_deleted = 0
            LIMIT 1";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param('i', $school_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Query execution error: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $license = $result->fetch_assoc();
        
        $response = [
            'success' => true,
            'message' => 'Active LMS license found',
            'licence_id' => $license['licence_id'],
            'available_qty' => $license['available_qty'],
            'subscription_type' => $license['subscription_type'],
            'class_id' => $license['class_id'],
            'board_id' => $license['board_id'],
            'expiry_date' => $license['expiry_date'],
            'can_create_student' => $license['available_qty'] > 0
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No active LMS license found for this school'
        ];
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>
