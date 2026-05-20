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

$school_id = $_GET['school_id'] ?? 0;
$sql = "SELECT batch_id, class_id, class_name, section, academic_year, board_id 
        FROM TX_CLASS_BATCHES 
        WHERE school_id = $school_id 
        ORDER BY class_id, section";

$result = $conn->query($sql);
$classes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['class_name'] = $row['class_name'] ?: 'Class ' . $row['class_id'];
        $classes[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $classes]);
$conn->close();
?>