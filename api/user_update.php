<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'config.php';

$response = ['success' => false, 'message' => 'Unknown error'];

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['user_id'])) {
        throw new Exception('Invalid input data');
    }
    
    $conn = getDbConnection();
    
    // Only update fields that commonly exist
    $updates = [];
    $params = [];
    
    // Email field
    if (isset($input['email']) && !empty($input['email'])) {
        if (columnExists($conn, 'users', 'email')) {
            $updates[] = "email = ?";
            $params[] = $input['email'];
        } elseif (columnExists($conn, 'users', 'email_id')) {
            $updates[] = "email_id = ?";
            $params[] = $input['email'];
        }
    }
    
    // Mobile field
    if (isset($input['mobile_number']) && !empty($input['mobile_number'])) {
        if (columnExists($conn, 'users', 'mobile_number')) {
            $updates[] = "mobile_number = ?";
            $params[] = $input['mobile_number'];
        } elseif (columnExists($conn, 'users', 'phone')) {
            $updates[] = "phone = ?";
            $params[] = $input['mobile_number'];
        } elseif (columnExists($conn, 'users', 'mobile')) {
            $updates[] = "mobile = ?";
            $params[] = $input['mobile_number'];
        }
    }
    
    // Status field
    if (isset($input['user_status']) && !empty($input['user_status'])) {
        if (columnExists($conn, 'users', 'user_status')) {
            $updates[] = "user_status = ?";
            $params[] = $input['user_status'];
        } elseif (columnExists($conn, 'users', 'status')) {
            $updates[] = "status = ?";
            $params[] = $input['user_status'];
        }
    }
    
    // Subscription field
    if (isset($input['subscription_type']) && !empty($input['subscription_type'])) {
        if (columnExists($conn, 'users', 'subscription_type')) {
            $updates[] = "subscription_type = ?";
            $params[] = $input['subscription_type'];
        }
    }
    
    // Joining Date
    if (isset($input['joining_date']) && !empty($input['joining_date'])) {
        if (columnExists($conn, 'users', 'joining_date')) {
            $updates[] = "joining_date = ?";
            $params[] = $input['joining_date'];
        }
    }
    
    // Expiry Date
    if (isset($input['expiry_date']) && !empty($input['expiry_date'])) {
        if (columnExists($conn, 'users', 'expiry_date')) {
            $updates[] = "expiry_date = ?";
            $params[] = $input['expiry_date'];
        }
    }
    
    if (empty($updates)) {
        throw new Exception('No valid fields to update');
    }
    
    // Add user_id to params
    $params[] = $input['user_id'];
    
    $query = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $types = str_repeat('s', count($params) - 1) . 'i';
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'User updated successfully'];
    } else {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;

// Helper function to check if column exists
function columnExists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    return $result->num_rows > 0;
}
?>