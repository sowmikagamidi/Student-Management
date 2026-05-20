<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'TX_SCHOOL_LICENCE'");
if ($tableCheck->num_rows == 0) {
    echo json_encode(['error' => 'Table TX_SCHOOL_LICENCE does not exist']);
    $conn->close();
    exit;
}

// Get column info
$columns = $conn->query("DESCRIBE TX_SCHOOL_LICENCE");
$colList = [];
while ($col = $columns->fetch_assoc()) {
    $colList[] = $col['Field'];
}

echo json_encode([
    'success' => true,
    'message' => 'Database connection successful',
    'table_exists' => true,
    'columns' => $colList
]);

$conn->close();
?>