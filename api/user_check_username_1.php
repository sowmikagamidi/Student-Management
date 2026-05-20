<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = ['exists' => false, 'suggestions' => []];

try {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'tutorix_db';

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception('Connection failed');
    }

    $username = isset($_GET['username']) ? trim($_GET['username']) : '';
    $exclude_id = isset($_GET['exclude_id']) ? intval($_GET['exclude_id']) : 0;

    if (empty($username)) {
        throw new Exception('Username is required');
    }

    // Validate username format
    if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
        throw new Exception('Username can only contain letters, numbers, dots, and underscores');
    }

    // Check if username exists
    $sql = "SELECT user_id FROM USERS WHERE user_name = ? AND is_deleted = 0";
    
    if ($exclude_id > 0) {
        $sql .= " AND user_id != ?";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    if ($exclude_id > 0) {
        $stmt->bind_param('si', $username, $exclude_id);
    } else {
        $stmt->bind_param('s', $username);
    }

    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    // Generate suggestions if exists
    $suggestions = [];
    if ($exists) {
        $base = preg_replace('/[0-9]+$/', '', $username);
        for ($i = 1; $i <= 5; $i++) {
            $suggestions[] = $base . $i;
        }
    }

    $response = [
        'exists' => $exists,
        'suggestions' => $suggestions,
        'available' => !$exists
    ];

    $conn->close();

} catch (Exception $e) {
    $response = [
        'exists' => false,
        'suggestions' => [],
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);
?>
