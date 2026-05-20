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
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if api_key column exists
$result = $conn->query("SHOW COLUMNS FROM TX_SCHOOL_LICENCE LIKE 'api_key'");

if ($result && $result->num_rows === 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE TX_SCHOOL_LICENCE ADD COLUMN api_key VARCHAR(100) UNIQUE AFTER expiry_date";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'api_key column added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding column: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => true, 'message' => 'api_key column already exists']);
}

$conn->close();
?>
