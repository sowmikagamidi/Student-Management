<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'tutorix_db';

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    $school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;

    if ($school_id == 0) {
        throw new Exception('School ID required');
    }

    $sql = "SELECT
                u.user_id,
                u.school_id,
                u.full_name,
                u.user_name,
                u.email_id,
                u.mobile_number,
                u.country_code,
                u.gender,
                u.user_type,
                u.user_status as display_status,
                u.current_class,
                u.created_dtm as created_at
            FROM USERS u
            WHERE u.school_id = ?
            ORDER BY u.user_id DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param('i', $school_id);
    if (!$stmt->execute()) {
        throw new Exception('Query execution failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $users = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $roleMap = [
                'SU' => 'student',
                'T' => 'teacher',
                'M' => 'mentor',
                'A' => 'admin'
            ];
            $row['role'] = isset($roleMap[$row['user_type']]) ? $roleMap[$row['user_type']] : $row['user_type'];

            $row['full_name'] = $row['full_name'] ?? '';
            $row['user_name'] = $row['user_name'] ?? '';
            $row['email_id'] = $row['email_id'] ?? '';
            $row['mobile_number'] = $row['mobile_number'] ?? '';
            $row['country_code'] = $row['country_code'] ?? '+91';
            $row['gender'] = $row['gender'] ?? '';
            $row['display_status'] = $row['display_status'] ?? 'A';
            $row['current_class'] = $row['current_class'] ?? '';
            $row['created_at'] = $row['created_at'] ?? '';

            $users[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'data' => $users]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'data' => []]);
}
?>