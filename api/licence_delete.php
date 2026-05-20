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
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || empty($data['licence_id'])) {
        throw new Exception('Invalid data or missing licence_id');
    }

    $licence_id = intval($data['licence_id']);

    // Check if licence exists
    $result = $conn->query("SELECT licence_id FROM TX_SCHOOL_LICENCE WHERE licence_id = $licence_id");
    if (!$result || $result->num_rows === 0) {
        throw new Exception('Licence not found');
    }

    // Soft delete - mark as deleted
    $sql = "UPDATE TX_SCHOOL_LICENCE SET is_deleted = 1, updated_dtm = NOW() WHERE licence_id = $licence_id";

    if (!$conn->query($sql)) {
        throw new Exception('Database error: ' . $conn->error);
    }

    echo json_encode(['success' => true, 'message' => 'Licence deleted successfully']);
    $conn->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
