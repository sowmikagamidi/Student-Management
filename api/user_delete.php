<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = ['success' => false, 'message' => 'Unknown error'];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['user_id'])) {
        throw new Exception('User ID is required');
    }

    $conn = new mysqli('localhost', 'root', '', 'tutorix_db');

    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    $user_id = intval($data['user_id']);

    // Get user details
    $userQuery = $conn->prepare("SELECT school_id, user_type FROM USERS WHERE user_id = ? AND (is_deleted = 0 OR is_deleted IS NULL)");
    if (!$userQuery) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $userQuery->bind_param('i', $user_id);
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    if ($userResult->num_rows === 0) {
        throw new Exception('User not found');
    }

    $user = $userResult->fetch_assoc();
    $userQuery->close();

    $school_id = $user['school_id'];
    $user_type = $user['user_type'];

    // If user is a student, increase available quantity of LMS license
    if ($user_type === 'SU') {
        $licenseQuery = $conn->prepare("
            SELECT licence_id, subscription_qty, available_qty FROM TX_SCHOOL_LICENCE
            WHERE school_id = ? AND licence_type = 'lms' AND (is_deleted = 0 OR is_deleted IS NULL) AND available_qty < subscription_qty
            ORDER BY licence_id DESC LIMIT 1
        ");
        
        if ($licenseQuery) {
            $licenseQuery->bind_param('i', $school_id);
            $licenseQuery->execute();
            $licenseResult = $licenseQuery->get_result();

            if ($licenseResult->num_rows > 0) {
                $license = $licenseResult->fetch_assoc();
                $licence_id = $license['licence_id'];

                $updateQuery = $conn->prepare("
                    UPDATE TX_SCHOOL_LICENCE
                    SET available_qty = available_qty + 1
                    WHERE licence_id = ?
                ");
                if ($updateQuery) {
                    $updateQuery->bind_param('i', $licence_id);
                    $updateQuery->execute();
                    $updateQuery->close();
                }
            }
            $licenseQuery->close();
        }
    }

    // Soft delete the user
    $deleteQuery = $conn->prepare("UPDATE USERS SET user_status = 'D', is_deleted = 1 WHERE user_id = ?");
    if (!$deleteQuery) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $deleteQuery->bind_param('i', $user_id);

    if ($deleteQuery->execute()) {
        $response = [
            'success' => true,
            'message' => 'User deleted successfully'
        ];
    } else {
        throw new Exception('Database error: ' . $deleteQuery->error);
    }

    $deleteQuery->close();
    $conn->close();

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
?>