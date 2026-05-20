<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'TX_SCHOOL_LICENCE'");
if ($tableCheck->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'TX_SCHOOL_LICENCE table does not exist']);
    $conn->close();
    exit;
}

// Get all LMS licences
$result = $conn->query("SELECT * FROM TX_SCHOOL_LICENCE WHERE licence_type = 'lms'");
$licences = [];
while ($row = $result->fetch_assoc()) {
    $licences[] = $row;
}

echo json_encode([
    'success' => true,
    'licences' => $licences,
    'count' => count($licences)
]);

$conn->close();
?>