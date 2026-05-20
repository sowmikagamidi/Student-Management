<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'tutorix_db';

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        throw new Exception('DB Connection failed: ' . $conn->connect_error);
    }

    $school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;

    $response = ['success' => true, 'data' => ['lms' => [], 'tv' => []]];

    if ($school_id > 0) {
        // Get LMS licences (only active ones)
        $result = $conn->query("SELECT * FROM TX_SCHOOL_LICENCE WHERE school_id = $school_id AND licence_type = 'lms' AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY created_dtm DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response['data']['lms'][] = $row;
            }
        }

        // Get TV licences (only active ones)
        $result = $conn->query("SELECT * FROM TX_SCHOOL_LICENCE WHERE school_id = $school_id AND licence_type = 'tv' AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY created_dtm DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response['data']['tv'][] = $row;
            }
        }
    }

    echo json_encode($response);
    $conn->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
