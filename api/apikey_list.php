<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;

if ($school_id == 0) {
    echo json_encode(['success' => false, 'message' => 'School ID required']);
    exit;
}

$sql = "SELECT a.*, s.school_name, 
        CASE 
            WHEN a.used = 'Y' THEN 'Used'
            ELSE 'Not Used'
        END as used_status,
        CASE 
            WHEN a.validity < NOW() THEN 'Expired'
            WHEN a.used = 'Y' THEN 'Activated'
            ELSE 'Active'
        END as key_status
        FROM TX_SCHOOL_API_KEYS a
        JOIN TX_SCHOOL_DETAILS s ON a.school_id = s.school_id
        WHERE a.school_id = $school_id
        ORDER BY a.id DESC";

$result = $conn->query($sql);
$keys = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $keys[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $keys]);
$conn->close();
?>