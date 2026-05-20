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

$sql = "SELECT batch_id, class_id, class_name, section, academic_year 
        FROM TX_CLASS_BATCHES 
        WHERE school_id = $school_id 
        ORDER BY class_id, section";
$result = $conn->query($sql);
$batches = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['display_name'] = $row['class_name'] . ' (' . $row['section'] . ') - ' . $row['academic_year'];
        $batches[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $batches]);
$conn->close();
?>