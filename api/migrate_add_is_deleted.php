<?php
/**
 * Migration Script: Add is_deleted column to TX_SCHOOL_LICENCE table
 * This script adds soft delete support for licences
 */

header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

try {
    // Check if column already exists
    $checkSql = "SHOW COLUMNS FROM TX_SCHOOL_LICENCE LIKE 'is_deleted'";
    $result = $conn->query($checkSql);
    
    if ($result && $result->num_rows === 0) {
        // Column doesn't exist, add it
        $alterSql = "ALTER TABLE TX_SCHOOL_LICENCE ADD COLUMN is_deleted TINYINT(1) DEFAULT 0 AFTER expiry_date";
        
        if ($conn->query($alterSql)) {
            echo json_encode([
                'success' => true,
                'message' => 'Column is_deleted added successfully to TX_SCHOOL_LICENCE table'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error adding column: ' . $conn->error
            ]);
        }
    } else {
        // Column already exists
        echo json_encode([
            'success' => true,
            'message' => 'Column is_deleted already exists in TX_SCHOOL_LICENCE table'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
